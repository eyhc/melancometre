import { updateChart } from "./chart";
import { addData, getLastData } from "./db";
import imgUrl from "./img/logo.png";

export interface State {
    moral: string,
    energy: string,
    sleep: string,
    suic_ideas: string
}

export class MyApp {
    public state: State = {
        moral: "100",
        energy: "100",
        sleep: "100",
        suic_ideas: "0"
    };

    constructor() {
        getLastData()
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

    public logo = imgUrl;

    public error = false;

    public addState() {
        const d = {
            date: Date.now(),
            moral: Number(this.state.moral),
            sleep: Number(this.state.sleep),
            energy: Number(this.state.energy),
            suicidal_ideas: Number(this.state.suic_ideas)
        }
        addData(d).then(() => {
            this.error = false;
        }).catch(() => {
            this.error = true;
        });

        updateChart();
    }

    public openConfig() {
        console.log("openConfig");
    }
}
