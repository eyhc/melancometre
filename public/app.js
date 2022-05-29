var app = angular.module('app', []);

app.filter('getGIcon', function() {
    function iconFilter(input) {
        if (input < 20) return "fa fa-smog text-5";
        if (input < 40) return "fa fa-cloud-showers-heavy text-4";
        if (input < 60) return "fa fa-cloud-sun-rain text-3";
        if (input < 80) return "fa fa-cloud-sun text-2";
        return "fa fa-sun text-1";
    }
    iconFilter.$stateful = true;
    return iconFilter;
})
.filter('getMIcon', function() {
    function iconFilter(input) {
        if (input < 20) return "fa fa-sad-tear text-5";
        if (input < 40) return "fa fa-frown text-4";
        if (input < 60) return "fa fa-meh text-3";
        if (input < 80) return "fa fa-smile text-2";
        return "fa fa-smile-beam text-1";
    }
    iconFilter.$stateful = true;
    return iconFilter;
})
.filter('getEIcon', function() {
    function iconFilter(input) {
        if (input < 20) return "fa fa-battery-empty text-5";
        if (input < 40) return "fa fa-battery-quarter text-4";
        if (input < 60) return "fa fa-battery-half text-3";
        if (input < 80) return "fa fa-battery-three-quarters text-2";
        return "fa fa-battery-full text-1";
    }
    iconFilter.$stateful = true;
    return iconFilter;
})
.filter('getSIIcon', function() {
    function iconFilter(input) {
        if (input < 20) return "fa fa-smile-beam text-1";
        if (input < 40) return "fa fa-smile text-2";
        if (input < 60) return "fa fa-meh text-3";
        if (input < 80) return "fa fa-frown text-4";
        return "fa fa-sad-tear text-5";
    }
    iconFilter.$stateful = true;
    return iconFilter;
});

//qcm controller
app.controller('qcm', ['$scope', function ($scope) {
    $scope.general = 100;
    $scope.moral = 100;
    $scope.energy = 100;
    $scope.suicidal_ideas = 0;
    $scope.error = false;

    var xhr;
    var form = new FormData();
    if (xhr != undefined && xhr != null) { xhr.abort(); }

    xhr = new XMLHttpRequest();
    xhr.open('POST', 'call.php');

    form.append('action', 'get_last_data');
    xhr.send(form);

    xhr.addEventListener('readystatechange', function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var obj = JSON.parse(xhr.responseText);
            if (obj.success == 1) {
                $scope.general = obj.last_state.general;
                $scope.moral = obj.last_state.moral;
                $scope.energy = obj.last_state.energy;
                $scope.suicidal_ideas = obj.last_state.suicidal_ideas;
                $scope.$apply();
            }
        }
    });

    update_chart();

    $scope.addData = function() {
        var xhr;
        var form = new FormData();
        if (xhr != undefined && xhr != null) { xhr.abort(); }

        xhr = new XMLHttpRequest();
        xhr.open('POST', 'call.php');

        form.append('action', 'add_data');
        form.append('general', document.getElementById('general').value);
        form.append('moral', document.getElementById('moral').value);
        form.append('energy', document.getElementById('energy').value);
        form.append('suicidal_ideas', document.getElementById('suicidal_ideas').value);
        xhr.send(form);

        xhr.addEventListener('readystatechange', function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                var obj = JSON.parse(xhr.responseText);
                if (obj.success == 1) {
                    $scope.error = false;
                    $scope.$apply();
                    update_chart();
                }
                else {
                    $scope.error = true;
                    $scope.$apply();
                }
            }
            else if (xhr.readyState === XMLHttpRequest.DONE && xhr.status !== 200) {
                $scope.error = true;
                $scope.$apply();
            }
        });
    };
}]);

window.onresize = function() {
    c_height = window.innerHeight - ($(".navbar")[0].offsetHeight + $('.col-md-8 > .border-bottom')[0].offsetHeight);
    c_width = $('#myChart')[0].offsetWidth;

    myChart.options.aspectRatio = c_width / c_height;
    //myChart.options.maintainAspectRatio = false;
    myChart.canvas.parentNode.style.height = c_height + "px !important";
}