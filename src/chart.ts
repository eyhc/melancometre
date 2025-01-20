import { Chart } from 'chart.js'
import { getData as getState } from './db';
import { DataTransformer } from './transform-data';

export interface ChartData {
    labels: string[];
    moral: number[];
    energy: number[];
    sleep: number[];
    suic_ideas: number[];
}

/* sample of colors for charts */
const chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(51, 55, 63)'
};

/* Each dataset labels */
const chartLabels = ["Moral", "Sommeil", "Energie", "Idées suicidaires"];

/* the default canvas for chartjs in document */
let ctx: HTMLCanvasElement | null;
let chart : Chart | null;

export function initChart() {
    ctx = document.getElementById('chart') as HTMLCanvasElement;

    /* the default Chart object */
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    data: [],
                    label: chartLabels[0],
                    borderColor: chartColors.green,
                    backgroundColor: chartColors.green,
                    pointBackgroundColor: chartColors.green,
                    pointBorderColor: chartColors.green,
                    tension: .25
                },
                {
                    data: [],
                    label: chartLabels[1],
                    borderColor: chartColors.purple,
                    backgroundColor: chartColors.purple,
                    pointBackgroundColor: chartColors.purple,
                    pointBorderColor: chartColors.purple,
                    tension: .25
                },
                {
                    data: [],
                    label: chartLabels[2],
                    borderColor: chartColors.red,
                    backgroundColor: chartColors.red,
                    pointBackgroundColor: chartColors.red,
                    pointBorderColor: chartColors.red,
                    tension: .25
                },
                {
                    data: [],
                    label: chartLabels[3],
                    borderColor: chartColors.orange,
                    backgroundColor: chartColors.orange,
                    pointBackgroundColor: chartColors.orange,
                    pointBorderColor: chartColors.orange,
                    tension: .25
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    updateChart();
}

export function updateChart() {
    getState().then((states) => {
        const data = DataTransformer.transform(states);
        setData(data);
    });
}

/**
 * 
 * @param data 
 * @returns 
 */
export function setData(data: ChartData): void {
    const d = chart?.data;
    if (!d || !d.datasets[0] || !d.datasets[1] || !d.datasets[2] || !d.datasets[3])
        return console.log("chart not exists !");

    d.labels = data.labels;
    d.datasets[0].data = data.moral;
    d.datasets[1].data = data.sleep;
    d.datasets[2].data = data.energy;
    d.datasets[3].data = data.suic_ideas;
    if (chart) chart.update();
}

/**
 * 
 * @returns 
 */
export function getData(): ChartData {
    const l = chart?.data.labels;
    const m = chart?.data.datasets[0]?.data;
    const s = chart?.data.datasets[1]?.data;
    const e = chart?.data.datasets[2]?.data;
    const si = chart?.data.datasets[3]?.data;
    return {
        labels: (l) ? l as string[] : [],
        moral: (m) ? m as number[] : [],
        energy: (e) ? e as number[] : [],
        sleep: (s) ? s as number[] : [],
        suic_ideas: (si) ? si as number[] : []
    }
}


// If window is resized then chart is resized
window.addEventListener("resize", () => {
    if (chart) {
        chart.resize();
    }
})