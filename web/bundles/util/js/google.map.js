function initMap() {
        var uluru = { lat: $('#lat').data('lat'), lng: $('#lng').data('lng')};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 8,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
        
      }
