/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import * as d3 from "d3";
import {Chart} from "chart.js";

console.log('coucou');
//
// document.addEventListener('DOMContentLoaded', function(){
//
//     const data = {
//         labels: ['Février', 'Mars', 'Avril', 'Mai', 'juin'],
//         datasets: [{
//             label: 'Des trucs',
//             data: [6, 9, 20, 7, 3],
//             backgroundColor: 'rgba(75, 192, 192, 0.2)',
//             borderColor: 'rgba(75, 192, 192, 1)',
//             borderWidth: 1
//         }]
//     };
//
//     const config = {
//         type: 'line',
//         data: data,
//     };
//
//     const ctx = document.querySelector('#graph').getContext('2d');
//     const myChart = new Chart(ctx, config);
//
// })