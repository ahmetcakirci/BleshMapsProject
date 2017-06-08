angular.module('mapsApp', [])
.directive('modalDialog', function($http) {
    return {
        restrict: 'AE',
        scope: {
            idbecon:'=',
            descbecon:'=',
            show: '='
        },
        replace: true,
        require: '?ngModel',
        transclude: true,
        link: function(scope, element, attrs) {
            scope.dialogStyle = {};
            if (attrs.width)
                scope.dialogStyle.width = attrs.width;
            if (attrs.height)
                scope.dialogStyle.height = attrs.height;
            scope.hideModal = function() {
                scope.show = false;
            };
            scope.updateBecon=function () {
                $http({
                    url: 'http://maps.ahmetcakirci.com/index.php/Maps/updateDescriptionForTest/'+scope.idbecon,
                    method: "POST",
                    headers: {'Content-Type': 'application/json'},
                    data: JSON.stringify({desc: scope.descbecon})
                }).then(function (response) {
                    alert(JSON.parse(response.data)['message']);
                });
                scope.show = false;
            }
        },
        template:"<div class='ng-modal' ng-show='show'><div class='ng-modal-overlay' ng-click='hideModal()'></div><div class='ng-modal-dialog' ng-style='dialogStyle'><div class='ng-modal-close' ng-click='hideModal()'>X</div><div class='ng-modal-dialog-content' ng-transclude></div><textarea ng-model='descbecon' class='form-control' rows='5'>{{descbecon}}</textarea><div class='text-center'><button class='btn btn-primary' ng-click='updateBecon()'>Güncelle</button></div></div></div>"
    };
})
.controller('MapCtrl', function ($scope,$http) {
    $scope.id=0;
    $scope.desc=0;
    $scope.modalShown = false;
    $scope.toggleModal = function(markerId,markerDescription) {
        $scope.id=markerId;
        $scope.desc=markerDescription;
        $scope.modalShown = !$scope.modalShown;
    };

    $scope.getBecons=function () {
        $http({
            url: 'http://maps.ahmetcakirci.com/index.php/Maps/getBeaconsForTest',
            method: "POST",
            headers: {'Content-Type': 'application/json'}
        })
            .then(function (response) {
                $scope.becons=[];
                $scope.becons=response.data;
                var mapOptions = {
                    zoom: 6,
                    center: new google.maps.LatLng(38.8799324, 30.7501374),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                $scope.map = new google.maps.Map(document.getElementById('map'), mapOptions);

                $scope.markers = [];

                var infoWindow = new google.maps.InfoWindow();

                var createMarker = function (info){

                    var marker = new google.maps.Marker({
                        map: $scope.map,
                        position: new google.maps.LatLng(info.latitude, info.longitude),
                        id:info.id,
                        deviceserial: info.deviceserial,
                        description:info.description
                    });
                    marker.content = '<div class="infoWindowContent">' + info.description + '</div>';

                    google.maps.event.addListener(marker, 'click', function(){
                        infoWindow.setContent('<h3>' + marker.deviceserial + '</h3>' + marker.content);
                        infoWindow.open($scope.map, marker);
                    });

                    $scope.markers.push(marker);
                }

                for (i = 0; i < $scope.becons.length; i++){
                    createMarker($scope.becons[i]);
                }

                $scope.openInfoWindow = function(e, selectedMarker){
                    e.preventDefault();
                    google.maps.event.trigger(selectedMarker, 'click');
                }
            });
    }
});