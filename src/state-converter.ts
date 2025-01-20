/**
 * This package
 * 
 * @author Elie Carrot
 * @packageDocumentation
 */

import { valueConverter } from 'aurelia';

/**
 * 
 */
@valueConverter('toMoralIcon')
export class MoralConverter {
    toView(value: number) {
        if (value < 20) return "bi bi-thermometer text-5";
        if (value < 40) return "bi bi-thermometer-low text-4";
        if (value < 60) return "bi bi-thermometer-half text-3";
        if (value < 80) return "bi bi-thermometer-high text-2";
        return "bi bi-thermometer-sun text-1";
    }
}

/**
 * 
 */
@valueConverter('toEnergyIcon')
export class EnergyConverter {
    toView(value: number) {
        if (value < 20) return "bi bi-battery text-5";
        if (value < 40) return "bi bi-battery-half text-4";
        if (value < 60) return "bi bi-battery-half text-3";
        if (value < 80) return "bi bi-battery-full text-2";
        return "bi bi-battery-charging text-1";
    }
}

/**
 * 
 */
@valueConverter('toSleepIcon')
export class SleepConverter {
    toView(value: number) {
        if (value < 20) return "bi bi-cloud-haze-fill text-5";
        if (value < 40) return "bi bi-clouds-fill text-4";
        if (value < 60) return "bi bi-cloud-moon-fill text-3";
        if (value < 80) return "bi bi-moon-fill text-2";
        return "bi bi-moon-stars-fill text-1";
    }
}

/**
 * 
 */
@valueConverter('toSuicIdeasIcon')
export class SuicIdeasConverter {
    toView(value: number) {
        if (value < 20) return "bi bi-emoji-grin text-1";
        if (value < 40) return "bi bi-emoji-smile text-2";
        if (value < 60) return "bi bi-emoji-neutral text-3";
        if (value < 80) return "bi bi-emoji-frown text-4";
        return "bi bi-emoji-tear text-5";
    }
}