1. Jquery Parser pakai JQ

git clone https://github.com/stedolan/jq.git
cd jq
autoreconf -i
./configure --disable-maintainer-mode
make
sudo make install

2. Daemon pakai nohup

https://www.npmjs.com/package/nohup
npm i nohup -g
/usr/lib/node_modules/nohup/bin/nohup ./verifyfile.py


===
proses:
- convert to ogg,webm.
+ dijalankan pake verifyfile.py dipanggil pake nohub
+ verifyfile.py menjalankan verifyfile.sh berdasarkan file di folder bot/verify
