/**
 * 
 * 
 * @packageDocumentation
 */

import { MetricChart } from "./chart";
import { DataTransformer } from "./transform-data";
import imgUrl from "./img/logo.png";
import { NotesDB } from "./db-notes";
import { MetricsDB } from "./db-metrics";


/**
 * 
 */
export interface State {
    moral: string,
    energy: string,
    sleep: string,
    suic_ideas: string
}

/**
 * 
 */
export class MyApp {
    public state: State = {
        moral: "100",
        energy: "100",
        sleep: "100",
        suic_ideas: "0"
    };

    private chart: MetricChart | undefined;
    private notesdb: NotesDB = new NotesDB();
    private metricsdb : MetricsDB = new MetricsDB();

    public logo = imgUrl;
    public error = false;

    public initChart() {
        this.chart = new MetricChart(
            document.getElementById("chart") as HTMLCanvasElement,
        );

        this.updateChart();
    }

    initDB() {
        this.openDB();

        this.metricsdb
        .create()
        .then(() => {
            return this.notesdb.create();
        })
        .then(() => {
            return this.metricsdb.getLast();
        })
        .then((data) => {
            this.state.moral = data.moral.toString();
            this.state.energy = data.energy.toString();
            this.state.sleep = data.sleep.toString();
            this.state.suic_ideas = data.suicidal_ideas.toString();
        })
        .catch(() => {
            this.state = {
                moral: "100",
                sleep: "100",
                energy: "100",
                suic_ideas: "0"
            }
        });
    }

    public addState() {
        const d = {
            date: Date.now(),
            moral: Number(this.state.moral),
            sleep: Number(this.state.sleep),
            energy: Number(this.state.energy),
            suicidal_ideas: Number(this.state.suic_ideas)
        }
        this.metricsdb.add(d)
        .then(() => {
            this.error = false;
        })
        .catch(() => {
            this.error = true;
        });

        this.updateChart();
    }

    public updateChart() {
        this.metricsdb.getAll()
        .then((states) => {
            const data = DataTransformer.transform(states);
            if (this.chart)
                this.chart.setData(data);
            else
                console.log("Chart doesn't exist");
        })
    }

    public openConfig() {
        console.log("openConfig");
    }

    public closeDB(): void {
        this.metricsdb.close()
        .then(() => {
            return this.notesdb.close();
        })
        .then(() => {
            // ok
            return;
        })
        .catch(() => {
            // TODO
        })

    }

    public openDB(): void {
        this.metricsdb.open();
        this.notesdb.open();
    }
}
