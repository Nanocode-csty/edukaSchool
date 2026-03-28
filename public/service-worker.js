self.addEventListener('install', function(event) {
  console.log('Eduka Service Worker instalado');
});
self.addEventListener('fetch', function(event) {
  // Puedes añadir lógica de caché aquí
});