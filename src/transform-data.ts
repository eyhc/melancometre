import type { ChartData } from "./chart";
import type { Sample } from "./db";

/**
 * it calculates incrementally the average of provided integers
 * @class
 */
class IncrementalAverage {
    private sum: number;
    private nb_elt: number;

    /**
     * Initialize attributes
     */
    constructor() {
        this.sum = 0;
        this.nb_elt = 0;
    }

    /**
     * Add a value to calculate the mean 
     * @param value the one to add
     */
    public add(value: number): void {
        this.sum += value;
        this.nb_elt++;
    }

    /**
     * Getter for the mean
     * @returns the current average
     */
    public getAverage(): number {
        if (this.nb_elt === 0) return 0;
        return this.sum / this.nb_elt;
    }

    /**
     * Reinitialize this.
     */
    public reset(): void {
        this.sum = 0;
        this.nb_elt = 0;
    }
}

/**
 * Object that transforms data from DB into Chart.js data format
 * It expose the main function only: static transform(data) -> chardata
 */
export class DataTransformer {
    protected data: Sample[] = [];
    protected labels: string[] = [];
    protected moral_data: number[] = [];
    protected energy_data: number[] = [];
    protected sleep_data: number[] = [];
    protected suic_ideas_data: number[] = [];

    /**
     * Initialize the "transformer" before {@link transform}
     * @param states the data to transform
     */
    protected setStates(states: Sample[]) {
        this.data = states;

        // clear all lists
        this.labels.length = 0;
        this.moral_data.length = 0;
        this.energy_data.length = 0;
        this.sleep_data.length = 0;
        this.suic_ideas_data.length = 0;
    }

    /**
     * @returns a Chart Data Object
     */
    protected getTransformedData(): ChartData {
        return {
            labels: this.labels,
            moral: this.moral_data,
            energy: this.energy_data,
            sleep: this.sleep_data,
            suic_ideas: this.suic_ideas_data
        };
    }

    /**
     * Transforms data from db to data for chart
     * In pratice, it calculates the average of each parameter per day
     * @param states the data to transform
     * @returns the corresponding chart data object
     */
    public static transform(states: Sample[]): ChartData  {
        const dataTransformer = new DataTransformer();
        dataTransformer.setStates(states);

        // initialization of "averagers"
        const moral_av = new IncrementalAverage();
        const sleep_av = new IncrementalAverage();
        const energy_av = new IncrementalAverage();
        const s_ideas_av = new IncrementalAverage();

        let last_date: string | undefined;
        let curr_date: string;
        for (const state of dataTransformer.data) {
            const date = new Date(state.date);
            curr_date = date.toLocaleDateString();
            if (!last_date) last_date = curr_date;

            if (last_date === curr_date) {
                moral_av.add(state.moral);
                energy_av.add(state.energy);
                sleep_av.add(state.sleep);
                s_ideas_av.add(state.suicidal_ideas);
            }
            else {
                dataTransformer.labels.push(last_date);
                this.pushResetAndAdd(
                    dataTransformer.moral_data, moral_av, state.moral
                );
                this.pushResetAndAdd(
                    dataTransformer.sleep_data, sleep_av, state.sleep
                );
                this.pushResetAndAdd(
                    dataTransformer.energy_data, energy_av, state.energy
                );
                this.pushResetAndAdd(
                    dataTransformer.suic_ideas_data, s_ideas_av, state.suicidal_ideas
                );

                last_date = curr_date;
            }
        }

        if (last_date) {
            dataTransformer.labels.push(last_date);
            dataTransformer.moral_data.push(moral_av.getAverage());
            dataTransformer.sleep_data.push(sleep_av.getAverage());
            dataTransformer.energy_data.push(energy_av.getAverage());
            dataTransformer.suic_ideas_data.push(s_ideas_av.getAverage());
        }

        return dataTransformer.getTransformedData();
    }

    /**
     * Add the average (of `av`) to the list. Reset the incremental-average
     * And Add `n` to `av`
     * @param list the list to add the mean
     * @param av corresponding incremental-average
     * @param n the number to add  
     */
    private static pushResetAndAdd(list: number[], av: IncrementalAverage, n: number) {
        list.push(av.getAverage());
        av.reset();
        av.add(n);
    }
}
