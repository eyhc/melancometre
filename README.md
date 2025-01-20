# Melancometre

This project is created with [aurelia](https://aurelia.io/) and [cordova](https://cordova.apache.org/)

## Dépendencies 

Please install cordova globally: [https://cordova.apache.org/docs/en/12.x-2025.01/guide/cli/installation.html](https://cordova.apache.org/docs/en/12.x-2025.01/guide/cli/installation.html)

next run:
    
    git clone https://github.com/eyhc/melancometre.git
    cd melancometre
    npm i
    cordova platform add browser
    cordova platform add android

for android refer to [https://cordova.apache.org/docs/en/12.x-2025.01/guide/platforms/android/index.html#the-required-software-&-tools](https://cordova.apache.org/docs/en/12.x-2025.01/guide/platforms/android/index.html#the-required-software-&-tools)

## Run on browser :

**N.B.** on browser, data aren't persistent

    npm run build && cordova run browser

or more simply

    npm start

## Run the app on android

    npm run build && cordova run android

## Documentation

Documentation may be generated with

    npm run doc

and then opening `docs/index.html`.
