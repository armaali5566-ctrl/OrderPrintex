async function testFollowing() {
  const urls = [
    'http://127.0.0.1:8080/cart/',
    'http://127.0.0.1:8080/checkout/'
  ];
  for (const u of urls) {
    const res = await fetch(u, { method: 'GET', redirect: 'manual' });
    console.log(`${u} -> Status: ${res.status} | Location: ${res.headers.get('location')}`);
  }
}
testFollowing();

