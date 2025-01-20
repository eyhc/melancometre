// Import our custom CSS
import './styles/style.scss'

import Aurelia from 'aurelia';
import { MyApp } from './my-app';
import { initChart } from './chart';
import { Chart, registerables } from 'chart.js'
import { closeDB, initDB } from './db';
import { LocalNotifications } from '@ionic-native/local-notifications';

document.addEventListener('deviceready', onDeviceReady);

function onDeviceReady() {
    document.addEventListener("pause", onPause, false);
    document.addEventListener("resume", onResume, false);

    initDB().then(() => {
        const x = Aurelia.app(MyApp).start();
        if (typeof x !== "undefined") {
            x.then(init);
        } else {
            init();
        }
    });
    window.screen.orientation.unlock();

    LocalNotifications.hasPermission().then((granted) => {
        if (granted) {
            scheduleDailyNotification();
        }
        else {
            LocalNotifications.requestPermission().then((granted) => {
                if (granted) {
                    scheduleDailyNotification();
                }
                else {
                    console.log("notification not allowed");
                }
            })
        }
    })
}

function onPause() {
    // save data by closing database
    closeDB().then(() => {
        if (navigator.splashscreen)
            navigator.splashscreen.show();
    });
}

function onResume() {
    // reopen database
    initDB().then(() => {
        if (navigator.splashscreen)
            navigator.splashscreen.hide();
    });
}

// 
function init() {
    Chart.register(...registerables)
    initChart();
    if (navigator.splashscreen)
        navigator.splashscreen.hide();
}

function scheduleDailyNotification() {
    LocalNotifications.schedule({
        id: 1,
        title: 'Melancometre',
        text: 'Penser a remplir votre tableau de bord aujourd\'hui',
        trigger: { every: { hour: 18, minute: 50 } },
        smallIcon: 'res://logo',
        foreground: true
    });
}