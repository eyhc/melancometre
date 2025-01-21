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

    private static chart: MetricChart | undefined;
    private static notesdb: NotesDB = new NotesDB();
    private static metricsdb : MetricsDB = new MetricsDB();

    public logo = imgUrl;
    public error = false;

    public initChart() {
        MyApp.chart = new MetricChart(
            document.getElementById("chart") as HTMLCanvasElement,
        );

        this.updateChart();
    }

    initDB() {
        MyApp.metricsdb
        .create()
        .then(() => {
            return MyApp.notesdb.create();
        })
        .then(() => {
            return MyApp.metricsdb.getLast();
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
        MyApp.metricsdb.add(d)
        .then(() => {
            this.error = false;
        })
        .catch(() => {
            this.error = true;
        });

        this.updateChart();
    }

    public updateChart() {
        MyApp.metricsdb.getAll()
        .then((states) => {
            const data = DataTransformer.transform(states);
            if (MyApp.chart)
                MyApp.chart.setData(data);
            else
                console.log("Chart doesn't exist");
        })
    }

    public openConfig() {
        console.log("openConfig");
    }

    public closeDB(): void {
        MyApp.metricsdb.close()
        .then(() => {
            return MyApp.notesdb.close();
        })
        .then(() => {
            // ok
            return;
        })
        .catch(() => {
            // TODO
        })

    }

    public reopenDB(): void {
        MyApp.metricsdb.reopen();
        MyApp.notesdb.reopen();
    }
}
