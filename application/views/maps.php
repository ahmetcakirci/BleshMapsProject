<html>
<head>
    <link rel="stylesheet" href="<?php echo '//maps.ahmetcakirci.com/assets/css/style.css';?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="http://maps.google.com/maps/api/js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
    <script src="<?php echo '//maps.ahmetcakirci.com/assets/js/script.js';?>"></script>
</head>
<body>
<div ng-app="mapsApp" ng-controller="MapCtrl" ng-init="getBecons()">
    <div id="map"></div>
    <div id="panel">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Device Serial</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="marker in markers|orderBy:'deviceserial'">
                <td><a href="#" ng-click="openInfoWindow($event, marker)">{{marker.deviceserial}}</a></td>
                <td>{{marker.description}}<button class="btn btn-primary pull-right" ng-click='toggleModal(marker.id,marker.description)'>Güncelle</button></td>
            </tr>
            </tbody>
        </table>
        <div class="text-center">
            <button type="button" ng-click="getBecons()" class="btn btn-default">GET NEW BEACONS</button>
        </div>
    </div>
    <modal-dialog idbecon='id' descbecon='desc' show='modalShown' width='400px' height='30%'>
        <p>Güncelleme Paneli<p>
    </modal-dialog>
</div>
</body>
</html>