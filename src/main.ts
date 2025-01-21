// Import our CSS (especially bootstrap)
import './styles/style.scss'

import Aurelia from 'aurelia';
import { MyApp } from './my-app';

/* an aurelia object */
const aurelia = new Aurelia();

/* listen cordova deviceready event */
document.addEventListener('deviceready', onDeviceReady);


/* initialization function after starting Aurelia */
function init() {
    // init chart
    const app = aurelia.container.get(MyApp);
    app.initDB(); // init db first
    app.initChart();

    // hide splashscreen (cordova)
    if (navigator.splashscreen)
        navigator.splashscreen.hide();
}

/* Schedule notification every day at 6:00 pm
 * see https://github.com/katzer/cordova-plugin-local-notifications
 */
function scheduleDailyNotification() {
    cordova.plugins.notification.local.schedule({
        title: 'Melancometre',
        text: 'Penser a remplir votre tableau de bord aujourd\'hui',
        trigger: { 
            every: {hour: 19, minute: 35}
        }
    });
}


/* Called when cordova app is ready */
function onDeviceReady() {
    // listen pause and resume events (cordova)
    // when app is in the background or back in the foreground
    document.addEventListener("pause", onPause, false);
    document.addEventListener("resume", onResume, false);

    // start aurelia
    const x = aurelia.app(MyApp).start();

    // then call init()
    if (typeof x !== "undefined") {
        x.then(init);
    } else {
        init();
    }
        
    // allow changing screen orientation (cordova)
    window.screen.orientation.unlock();

    // request permission for notification and schedule it
    cordova.plugins.notification.local.hasPermission((granted) => {
        if (granted) scheduleDailyNotification();
        else {
            cordova.plugins.notification.local.requestPermission((granted) => {
                if (granted) scheduleDailyNotification();
                else console.log("notification not allowed");
            });
        }
    });
}

/* Called when app is brought to the background */
function onPause() {
    // save data by closing database
    aurelia.container.get(MyApp).closeDB();
    if (navigator.splashscreen)
        navigator.splashscreen.show();
}

/* Called when app is brought to the foreground */
function onResume() {
    // reopen database
    aurelia.container.get(MyApp).openDB()
    if (navigator.splashscreen)
        navigator.splashscreen.hide();
}
