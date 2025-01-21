/**
 * Module that manages chart created with 
 * [chart.js](https://www.chartjs.org/) library 
 * about mental-health statistics interfaced in {@link ChartData}
 * 
 * __file:__ `chart.ts` \
 * __date:__ 19/01/2025 (fr)
 * @author Elie Carrot
 * @packageDocumentation
 */

import Chart from "chart.js/auto";

/**
 * Data interface used by the following class ({@link MetricChart})
 * @remarks Each list must have the same length. 
 * The elements with index `i` in all lists 
 * refer to the same date/day.
 * 
 * @interface
 */
export interface ChartData {
    /**
     * A date as string
     * like ("18/01/2001" or "Tue Jan 21 2025")
     * @remarks Prefer sorted in ascending order
     */
    labels: string[];

    /**
     * List of numbers between 0 and 100
     */
    moral: number[];

    /**
     * List of numbers between 0 and 100
     */
    energy: number[];

    /**
     * List of numbers between 0 and 100
     */
    sleep: number[];

    /**
     * List of numbers between 0 and 100
     */
    suic_ideas: number[];
}

/**
 * Represents a chart displaying mental health metrics in datasets.
 * The interface for data: {@link ChartData}.
 * @see {@link constructor}, {@link setData}, {@link getData}.
 * @class
 */
export class MetricChart {
    /** Sample of colors for charts */
    private static chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(51, 55, 63)'
    };

    /** Each dataset labels */
    private static chartLabels = [
        "Moral", "Sommeil", "Energie", "Idées suicidaires"
    ];


    /** the chart.js elt */
    private chart : Chart;

    /**
     * Create a linear chart from the "ctx" canvas using chart.js.
     * 
     * @see {@link https://www.chartjs.org/docs/latest/}
     * @param ctx a canvas
     */
    constructor(ctx: HTMLCanvasElement) {
        // create a new chart
        this.chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    // moral series
                    {
                        data: [],
                        label: MetricChart.chartLabels[0],
                        borderColor: MetricChart.chartColors.green,
                        backgroundColor: MetricChart.chartColors.green,
                        pointBackgroundColor: MetricChart.chartColors.green,
                        pointBorderColor: MetricChart.chartColors.green,
                        tension: .25
                    },
                    // sleep series
                    {
                        data: [],
                        label: MetricChart.chartLabels[1],
                        borderColor: MetricChart.chartColors.purple,
                        backgroundColor: MetricChart.chartColors.purple,
                        pointBackgroundColor: MetricChart.chartColors.purple,
                        pointBorderColor: MetricChart.chartColors.purple,
                        tension: .25
                    },
                    // energy series
                    {
                        data: [],
                        label: MetricChart.chartLabels[2],
                        borderColor: MetricChart.chartColors.red,
                        backgroundColor: MetricChart.chartColors.red,
                        pointBackgroundColor: MetricChart.chartColors.red,
                        pointBorderColor: MetricChart.chartColors.red,
                        tension: .25
                    },
                    // suicidal ideas series
                    {
                        data: [],
                        label: MetricChart.chartLabels[3],
                        borderColor: MetricChart.chartColors.orange,
                        backgroundColor: MetricChart.chartColors.orange,
                        pointBackgroundColor: MetricChart.chartColors.orange,
                        pointBorderColor: MetricChart.chartColors.orange,
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

        // If window is resized then chart is resized
        window.addEventListener("resize", () => {
            this.chart.resize();
        });
    }

    /**
     * Setter for data
     * @param data 
     * @returns 
     */
    setData(data: ChartData): void {
        const d = this.chart.data;
        if (d.datasets[0] && d.datasets[1] &&
            d.datasets[2] && d.datasets[3]
        ) {   
            d.labels = data.labels;
            d.datasets[0].data = data.moral;
            d.datasets[1].data = data.sleep;
            d.datasets[2].data = data.energy;
            d.datasets[3].data = data.suic_ideas;
            this.chart.update();
        }
    }

    /**
     * Getter for data
     * @returns a ChartData object
     */
    getData(): ChartData {
        const l = this.chart.data.labels;
        const m = this.chart.data.datasets[0];
        const e = this.chart.data.datasets[1];
        const s = this.chart.data.datasets[2];
        const si = this.chart.data.datasets[3];
        return {
            labels: (l) ? l as string[] : [],
            moral: (m) ? m.data as number[] : [],
            energy: (e) ? e.data as number[] : [],
            sleep: (s) ? s.data as number[] : [],
            suic_ideas: (si) ? si.data as number[] : [],
        };
    }
}
