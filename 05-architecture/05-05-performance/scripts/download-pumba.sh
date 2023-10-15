wget https://github.com/alexei-led/pumba/releases/download/0.7.6/pumba_linux_amd64
mv pumba_linux_amd64 pumba
chmod -R 755 ./pumba
docker pull gaiadocker/iproute2
./pumba
