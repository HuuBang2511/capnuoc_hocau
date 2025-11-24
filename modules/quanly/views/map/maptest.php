<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bản đồ Leaflet với TomTom Traffic</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
  <div id="map" style="width: 100%; height: 500px;"></div>
  <script>
    // Khởi tạo bản đồ tại TP. Hồ Chí Minh
    var map = L.map('map').setView([10.7769, 106.7009], 12);

    // Thêm lớp nền TomTom
    L.tileLayer('https://{s}.api.tomtom.com/map/1/tile/basic/main/{z}/{x}/{y}.png?key=B9SCLAWMBqMKfsGtt1U3inXajGt59lxu', {
      attribution: '© <a href="https://developer.tomtom.com">TomTom</a>',
      subdomains: 'abcd',
      maxZoom: 22
    }).addTo(map);

    // Thêm lớp lưu lượng giao thông TomTom với URL mới
    L.tileLayer('https://{s}.api.tomtom.com/traffic/map/4/tile/flow/relative0/{z}/{x}/{y}.png?key=B9SCLAWMBqMKfsGtt1U3inXajGt59lxu', {
      attribution: '© <a href="https://developer.tomtom.com">TomTom</a>',
      subdomains: 'abcd',
      maxZoom: 22,
      opacity: 0.7
    }).addTo(map);
  </script>
</body>
</html>