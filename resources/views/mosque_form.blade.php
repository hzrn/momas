<!-- Map Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">{{ __('mosque.select_location') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="map" style="height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('mosque.close') }}
                </button>
                <button type="button" class="btn btn-primary" id="saveLocation">
                    {{ __('mosque.save_location') }}
                </button>
            </div>
        </div>
    </div>
</div>

@extends('layouts.app_adminkit')

@section('content')
<h1 class="h3 mb-3">{{$title}}</h1>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5 class="card-title">{{__('mosque.fill')}}</h5>
            </div>
            <div class="card-body pt-0">
                {!! Form::model($mosque, [
                    'method' => 'POST',
                    'route' => 'mosque.store'
                ]) !!}

                <!-- CSRF Token -->
                {!! csrf_field() !!}

                <div class="form-group mb-3">
                    <label for="name">{{__('mosque.name')}}</label>
                    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                    <span class="text-danger">{!! $errors->first('name') !!}</span>
                </div>

                <div class="form-group mb-3">
                    <label for="address">{{__('mosque.address')}}</label>
                    {!! Form::text('address', null, ['class' => 'form-control', 'required']) !!}
                    <span class="text-danger">{!! $errors->first('address') !!}</span>
                </div>

                <div class="form-group">
                    {!! Form::label('phone_num', __('mosque.phone')) !!}
                    {!! Form::text('phone_num', null, [
                        'class' => 'form-control mb-3',
                        'required',
                        'pattern' => '[0-9]*',
                        'inputmode' => 'numeric',

                    ]) !!}
                    <span class="text-danger">{!! $errors->first('phone_num') !!}</span>
                </div>

                <div class="form-group mb-3">
                    {!! Form::label('email', __('mosque.email')) !!}
                    {!! Form::email('email', null, ['class' => 'form-control', 'required']) !!}
                    <span class="text-danger">{!! $errors->first('email') !!}</span>
                </div>

                <div class="form-group mb-3">
                    <label for="location">{{ __('mosque.location') }}</label>
                    <div class="input-group">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#mapModal">
                            {{ __('mosque.select_location') }}
                        </button>
                        {!! Form::text('location_name', null, [
                            'class' => 'form-control ms-2',
                            'id' => 'location_name',
                            'readonly' => 'readonly',
                            'placeholder' => __('mosque.no_location_selected')
                        ]) !!}
                    </div>
                    <!-- Existing hidden fields for latitude and longitude -->
                    {!! Form::hidden('latitude', null, ['id' => 'latitude']) !!}
                    {!! Form::hidden('longitude', null, ['id' => 'longitude']) !!}
                </div>


                {!! Form::submit(__('mosque.save'), ['class' => 'btn btn-primary']) !!}

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize variables
    let map, marker;

    // Coordinates for Malaysia (e.g., Kuala Lumpur)
    const defaultLat = 3.1390; // Latitude for Kuala Lumpur
    const defaultLng = 101.6869; // Longitude for Kuala Lumpur

    // Initialize the map when the modal opens
    $('#mapModal').on('shown.bs.modal', function () {
        if (!map) {
            // Create the map centered on Malaysia
            map = L.map('map').setView([defaultLat, defaultLng], 13);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add a draggable marker at Malaysia's default coordinates
            marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

            // Modify the existing event listeners to call updateLocationName
            marker.on('dragend', function () {
                const latLng = marker.getLatLng();
                document.getElementById('latitude').value = latLng.lat;
                document.getElementById('longitude').value = latLng.lng;
                updateLocationName(latLng.lat, latLng.lng);
            });

            // Add search functionality
            L.Control.geocoder().on('markgeocode', function (e) {
                const latLng = e.geocode.center;
                marker.setLatLng(latLng);
                map.setView(latLng, 13);
                document.getElementById('latitude').value = latLng.lat;
                document.getElementById('longitude').value = latLng.lng;
                updateLocationName(latLng.lat, latLng.lng);
            }).addTo(map);
        } else {
            // Ensure map renders correctly if modal is reopened
            map.invalidateSize();
        }
    });

    function updateLocationName(lat, lng) {
        // Use the Nominatim OpenStreetMap geocoding service to get the location name
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=10`)
            .then(response => response.json())
            .then(data => {
                let locationName = '';
                if (data.display_name) {
                    // You can customize how much of the address you want to display
                    locationName = data.display_name;
                    document.getElementById('location_name').value = locationName;
                }
            })
            .catch(error => {
                console.error('Error fetching location name:', error);
                document.getElementById('location_name').value = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
            });
    }

    // Save the selected location
    document.getElementById('saveLocation').addEventListener('click', function () {
        const latLng = marker.getLatLng();
        console.log("Latitude: ", latLng.lat);
        console.log("Longitude: ", latLng.lng);
        document.getElementById('latitude').value = latLng.lat;
        document.getElementById('longitude').value = latLng.lng;
        updateLocationName(latLng.lat, latLng.lng);
        $('#mapModal').modal('hide');
    });
</script>


@endsection
