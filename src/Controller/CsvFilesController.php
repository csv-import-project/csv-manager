<?php

namespace App\Controller;

use App\Entity\CsvFiles;
use App\Form\CsvFilesType;
use App\Repository\CsvFilesRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use function PHPUnit\Framework\directoryExists;

#[Route('/csv/files')]
class CsvFilesController extends AbstractController
{
    #[Route('/', name: 'app_csv_files_index', methods: ['GET'])]
    public function index(CsvFilesRepository $csvFilesRepository): Response
    {
        return $this->render('csv_files/index.html.twig', [
            'csv_files' => $csvFilesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_csv_files_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $csvFile = new CsvFiles();
        $form = $this->createForm(CsvFilesType::class, $csvFile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fileSystem = new Filesystem();

            $submittedFile = $form->get('url')->getData();
            $originalFilename = pathinfo($submittedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid('', false) . '.csv';
//            dd($safeFilename, $originalFilename, $newFilename);





            $rootDirectory = './assets/uploads/';
            if (!directoryExists($rootDirectory)) {
                $fileSystem->mkdir($rootDirectory);
            }
            $targetFile = $rootDirectory . $newFilename;
//            dd($targetFile);

            //now we can copy the new file in the right folder
            $fileSystem->copy($submittedFile, $targetFile);



            //setting new file url
            $csvFile->setUrl($targetFile);
//            $file->setFileName($newFilename);

            //setting new file size
//            $finder = new Finder();
//            $finder->files()->name($newFilename)->in($targetDirectory);
//
//            foreach ($finder as $fileItem) {
//
//                $fileSize = $fileItem->getSize();
//                $file->setFileSize($fileSize);
//            }



            $entityManager->persist($csvFile);
            $entityManager->flush();

            return $this->redirectToRoute('app_csv_files_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('csv_files/new.html.twig', [
            'csv_file' => $csvFile,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_csv_files_show', methods: ['GET'])]
    public function show(CsvFiles $csvFile, ChartBuilderInterface $chartBuilder): Response
    {
        $csv= file_get_contents($csvFile->getUrl());
        $array = array_map("str_getcsv", explode("\n", $csv));
        json_encode($array);
        $dataLength = count($array);
        $dataX = [];

        for ($i = 1; $i <= $dataLength; $i++) {
            array_push($dataX, $array[$i - 1] );
        }
//        dd($dataX);
//

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => [$array[0]],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [...$dataX],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);



        return $this->render('csv_files/show.html.twig', [
            'csv_file' => $csvFile,
            'chart' => $chart
//            'json' => $array
        ]);



    }

    #[Route('/{id}/edit', name: 'app_csv_files_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CsvFiles $csvFile, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CsvFilesType::class, $csvFile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fileSystem = new Filesystem();

            $submittedFile = $form->get('url')->getData();
            $originalFilename = pathinfo($submittedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid('', false) . '.csv';
            $rootDirectory = './assets/uploads/';
            if (!directoryExists($rootDirectory)) {
            $fileSystem->mkdir($rootDirectory);
            }
            $targetFile = $rootDirectory . $newFilename;
//            dd($targetFile);

            //now we can copy the new file in the right folder
            $fileSystem->copy($submittedFile, $targetFile);



            //setting new file url
            $csvFile->setUrl($targetFile);

            $entityManager->flush();

            return $this->redirectToRoute('app_csv_files_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('csv_files/edit.html.twig', [
            'csv_file' => $csvFile,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_csv_files_delete', methods: ['POST'])]
    public function delete(Request $request, CsvFiles $csvFile, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$csvFile->getId(), $request->request->get('_token'))) {
            $entityManager->remove($csvFile);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_csv_files_index', [], Response::HTTP_SEE_OTHER);
    }
}
