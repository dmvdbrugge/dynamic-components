#!/bin/bash
git clone --branch=release https://github.com/krakjoe/ui
cd ui
mkdir -p deps/{lib,include}
git clone --depth=1 https://github.com/andlabs/libui
cd libui
mkdir build
cd build
cmake ..
make -j4
cp out/libui.* ../../deps/lib
cp ../ui*.h ../../deps/include
cd ../..
phpize
./configure --with-ui=deps
make
cd ..
echo "extension=ui.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
