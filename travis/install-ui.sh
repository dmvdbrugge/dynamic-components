#!/bin/bash

# Get ui and setup dependency dirs
git clone --depth=2 https://github.com/krakjoe/ui
cd ui && git checkout 34276321ec891542b6a90da717a59d5066bac4ec
mkdir -p deps/{lib,include}

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
make && make install && echo "extension=ui.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini

# Return if building ui worked
exit $?
