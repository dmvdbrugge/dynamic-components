#!/bin/bash

# Clone ui and setup dependency dirs
git clone --branch=release https://github.com/krakjoe/ui
cd ui && mkdir -p deps/{lib,include}

# Clone and build libui
git clone --depth=1 https://github.com/andlabs/libui
cd libui && mkdir build && cd build
cmake ..
make -j4

# Copy built libui things to ui-dependency dirs
cp out/libui.* ../../deps/lib && cp ../ui*.h ../../deps/include

# Prepare and build ui
cd ../..
phpize && ./configure --with-ui=deps
make && echo "extension=ui.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini

# Return if building ui worked
exit $?
