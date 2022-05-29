<!doctype html>
<html class="h-100" ng-app="app">
    <?php include('head.html.php'); ?>
    <body class="d-flex flex-column h-100">
        <style>
            .icon { display: inline-block; font-size: 2rem; text-align: center; }

            .text-1 { color: #fabe3c; }
            .text-2 { color: #2fb4b3; }
            .text-3 { color: #62929e; }
            .text-4 { color: #54697a; }
            .text-5 { color: #b7b7b7; }

            .input-group > .ml-3 { min-width: 65px; text-align: right; }
            .input-group > .icon { min-width: 56px; }
        </style>

        <?php include('nav.html.php'); ?>

        <!-- main -->
        <div class="container-fluid h-100">
            <div class="row h-100">

                <!-- QCM -->
                <div class="col-md-4 px-4 bg-light border-left" ng-controller="qcm">
                    <div class="border-bottom pt-3 pb-2 mb-3">
                        <h1 class="h5"><?= trans('Where I am?') ?></h1>
                    </div>

                    <div class="alert alert-danger" role="alert" ng-show="error">
                        <strong><?= trans('Something went wrong!') ?></strong>
                        <?= trans('Please verify your form data or try it later') ?>.
                    </div>
                    <!-- general -->
                    <div class="form-group">
                        <label for="general"><?= trans('How are you?') ?></label>
                        <div class="input-group flex-nowrap">
                            <i class="icon mr-3 {{general | getGIcon}}"></i>
                            <div class="w-100">
                                <input type="range" class="custom-range" min="0" max="100" step="1" id="general" ng-model="general">
                                <div class="d-flex justify-content-between">
                                    <small><?= trans('Not great') ?></small>
                                    <small><?= trans('Medium') ?></small>
                                    <small><?= trans('Great') ?></small>
                                </div>
                            </div>
                            <span class="ml-3">{{general}}&nbsp;%</span>
                        </div>
                    </div>

                    <!-- moral -->
                    <div class="form-group">
                        <label for="moral"><?= trans('And moral?') ?></label>
                        <div class="input-group flex-nowrap">
                            <i class="icon mr-3 {{moral | getMIcon}}"></i>
                            <div class="w-100">
                                <input type="range" class="custom-range" min="0" max="100" step="1" id="moral" ng-model="moral">
                                <div class="d-flex justify-content-between">
                                    <small><?= trans('Not great') ?></small>
                                    <small><?= trans('Medium') ?></small>
                                    <small><?= trans('Great') ?></small>
                                </div>
                            </div>
                            <span class="ml-3">{{moral}}&nbsp;%</span>
                        </div>
                    </div>

                    <!-- energy -->
                    <div class="form-group">
                        <label for="energy"><?= trans('And energy?') ?></label>
                        <div class="input-group flex-nowrap">
                            <i class="icon mr-3 {{energy | getEIcon}}"></i>
                            <div class="w-100">
                                <input type="range" class="custom-range" min="0" max="100" step="1" id="energy" ng-model="energy">
                                <div class="d-flex justify-content-between">
                                    <small><?= trans('Not great') ?></small>
                                    <small><?= trans('Medium') ?></small>
                                    <small><?= trans('Great') ?></small>
                                </div>
                            </div>
                            <span class="ml-3">{{energy}}&nbsp;%</span>
                        </div>
                    </div>

                    <!-- suicidal ideas -->
                    <div class="form-group">
                        <label for="suicidal_ideas"><?= trans('Suicidal ideas?') ?></label>
                        <div class="input-group flex-nowrap">
                            <i class="icon mr-3 {{suicidal_ideas | getSIIcon}}"></i>
                            <div class="w-100">
                                <input type="range" class="custom-range" min="0" max="100" step="1" id="suicidal_ideas" ng-model="suicidal_ideas">
                                <div class="d-flex justify-content-between">
                                    <small><?= trans('Not at all') ?></small>
                                    <small><?= trans('A lot') ?></small>
                                </div>
                            </div>
                            <span class="ml-3">{{suicidal_ideas}}&nbsp;%</span>
                        </div>
                    </div>

                    <!-- valid -->
                    <div class="form-group text-center">
                        <button class="btn btn-primary" ng-click="addData()">
                            <i class="fa fa-save"></i>
                            <?= trans('Submit') ?>
                        </button>
                    </div>
                </div>

                <!-- CHART -->
                <div class="col-md-8 order-md-first">
                    <div class="border-bottom pt-3 pb-2 mb-3">
                        <h1 class="h5"><?= trans('My evolution') ?></h1>
                    </div>

                    <canvas id="myChart" width="800" height="400"></canvas>
                </div>
            </div>
        </div>

        <?php include('scripts.html'); ?>

        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.16/angular.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha512-s+xg36jbIujB2S2VKfpGmlC3T5V2TF3lY48DX7u2r9XzGzgPsa6wTpOQA7J9iffvdeBN0q9tKzRxVxw1JviZPg==" crossorigin="anonymous"></script>
        <script src="public/app.js"></script>
        <script src="public/chart.js"></script>
    </body>
</html>
