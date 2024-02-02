<?php
	/*
    * Show Last Modified Places - Map and List View
	* MapboxGL API
	* https://mapbox.com
	*/
    //$mapbox_token = get_theme_mod( 'mapbox_token' );
    //$mapbox_points = get_theme_mod( 'mapbox_points' );
    
    $site_url = get_site_url();
?>

<div class="row m-0 p-0">

    <div class="col-12 col-md-12 bg-dark p-0">

        <div id="title-latest">
            <div class="p-3 bg-primary d-flex align-items-center">
                <div class="text-light">
                    Son Eklenenler
                </div>
                <a class="btn btn-sm rounded-pill btn-light mx-3 py-0 px-2" href="arastir/l/?_sort=date_desc">
                    Tümü
                </a>
            </div>
        </div>

        <div id="ms-container" class="row m-0" data-masonry='{"percentPosition":true}' ></div>

        <div id="title-nearby" class="d-none">
            <div class="p-3 bg-primary d-flex align-items-center">
                <div class="text-light">
                    Etrafta 
                </div>
                <a id="nearby-link" class="btn btn-sm rounded-pill btn-light mx-3 py-0 px-2" href="arastir/h/?_sort=date_desc">
                    <span id="nearby-title"></span>
                </a>
            </div>
        </div>

        <div id="list-nearby" class="d-none">
            <div id="ms-container-nearby" class="row m-0" data-masonry='{"percentPosition":true}' ></div>
        </div>

    </div>

</div>

<script>
//// Map Nearby
    const options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    };

    function success(pos) {

        // Show/Hide Nearby Container
        const titleNearby = document.getElementById("title-nearby");
        titleNearby.classList.add("d-block");
        titleNearby.classList.remove("d-none");

        const listNearby = document.getElementById("list-nearby");
        listNearby.classList.add("d-block");
        listNearby.classList.remove("d-none");

        const crd = pos.coords;

        // console.log('Your current position is:');
        console.log(`Latitude : ${crd.latitude}`);
        console.log(`Longitude: ${crd.longitude}`);
        console.log(`More or less ${crd.accuracy} meters.`);

        let reverse = 'https://nominatim.openstreetmap.org/reverse?lat=' + crd.latitude + '&lon=' +  crd.longitude + '&format=geojson';
            //console.log(reverse);
            fetch(reverse)
            .then(reverse_response => reverse_response.json())
            .then(reverse_data => {

                let reverse_locations = reverse_data;
                //let location = reverse_locations.features[0].properties.display_name;
                if (typeof reverse_locations.features[0].properties.address.city !== 'undefined') {
                    //defined
                    city = reverse_locations.features[0].properties.address.city;

                } else {
                    //not defined
                    city = reverse_locations.features[0].properties.address.province;
                }

                let address = reverse_locations.features[0].properties.address.country + ', ' + city;
                let link = `harita/#15/${crd.latitude}/${crd.longitude}`;

                document.getElementById('nearby-title').innerHTML = address;
                document.getElementById('nearby-link').href = link;

                console.log(reverse_locations);
                
            }).catch(error => console.error(error))

        var latlon = `lat=${crd.latitude}&lon=${crd.longitude}&r=5000`;
        var	last_nearby = siteurl + '/wp-json/fa/images_radius?' + latlon;

        fetch(last_nearby)
        .then(response => response.json())
        .then(places => {

            last = places.features
            let text = [];
            let i;

            for (i = 0; i < last.length; i++) {

                if (!!last[i].properties.img) {
                text += `<a class="ms-item col-4 col-sm-4 col-md-3 col-lg-1 m-0 p-0 border border-light" onclick="imageModalFunction('${last[i].properties.id}')">
                            
                            <div class="card border-0 rounded-0">
                            
                                <div class="card-img rounded-0" style="height:15vh;max-height:150px;background-size:cover;background-position:center;background-image:url(${last[i].properties.img});">
                                </div>
                                
                                <div class="card-img-overlay p-1 d-flex align-items-end rounded-0">
                                    <div class="card-body p-0 px-1">
                                        <p class="mb-0 text-light small">● ${last[i].properties.distance} km</div></p>
                                    </div>
                                </div>

                            </div>

                        </a>`;

                } else {
                text += `<a class="ms-item col-4 col-sm-4 col-md-3 col-lg-1 m-0 p-0 border border-light">
                            <div class="card border-0 rounded-0">
                                <div class="card-title p-1 small">${last[i].properties.title} - ${last[i].properties.distance} km</div>
                            </div>
                        </a>`;
                }

            }

            let getlastPlacesList = document.getElementById('ms-container-nearby');
            getlastPlacesList.innerHTML = text;

            let elem = document.querySelector('#ms-container-nearby');
                let msnry = new Masonry( elem, {
                // options
                itemSelector: '.ms-item',
            });

        });
        
    }

    function error(err) {
        console.warn(`ERROR(${err.code}): ${err.message}`);
    }

    navigator.geolocation.getCurrentPosition(success, error, options);

//// Map Latest and Modified
    siteurl = '<?php echo $site_url; ?>';
    last_modified = siteurl + '/wp-json/fa/last_modified';
    last_added = siteurl + '/wp-json/fa/last_added';

    fetch(last_added)
    .then(response => response.json())
    .then(places => {

        last = places.features
        let text = [];
        let i;

        for (i = 0; i < last.length; i++) {

            if (!!last[i].properties.img) {
            text += `<a class="ms-item col-4 col-sm-4 col-md-3 col-lg-1 m-0 p-0 border border-light" onclick="imageModalFunction('${last[i].properties.id}')">
                        <div class="card border-0 rounded-0">
                            <div class="card-img rounded-0" style="height:15vh;max-height:150px;background-size:cover;background-position:center;background-image:url(${last[i].properties.img});">
                            </div>
                        </div>
                    </a>`;

            } else {
            text += `<a class="ms-item col-4 col-sm-4 col-md-3 col-lg-1 m-0 p-0 border border-light">
                        <div class="card border-0">
                            <div class="card-img-top">                            
                            </div>
                            <div class="card-title p-1 small">${last[i].properties.title}</div>
                        </div>
                    </a>`;
            }

        }

        let getlastPlacesList = document.getElementById('ms-container');
        getlastPlacesList.innerHTML = text;

        let elem = document.querySelector('#ms-container');
            let msnry = new Masonry( elem, {
            // options
            itemSelector: '.ms-item',
        });

    });

</script>
