#!/bin/bash

# Need to define different EXTENSION_DIR for OSX
mkdir extensions
EXT_DIR="`pwd`/extensions"

# Target ini differs per os too
if [[ "${TRAVIS_OS_NAME}" == "linux" ]]; then INI_TARGET=~/.phpenv/versions/$(phpenv version-name)/etc/php.ini; fi
if [[ "${TRAVIS_OS_NAME}" == "osx"   ]]; then INI_TARGET=/usr/local/etc/php/7.1/php.ini; fi

# Get ui and setup dependency dirs
wget https://pecl.php.net/get/UI-2.0.0.tgz -q
tar -xzf UI-2.0.0.tgz
cd UI-2.0.0 && mkdir -p deps/{lib,include}

# Get and build libui
wget https://github.com/andlabs/libui/archive/ce37d12d230cea529bf6f5ac1d3bc76b9a75bbbd.zip -qO libui.zip
unzip -q libui.zip && mv libui-* libui
cd libui && mkdir build && cd build
cmake ..
make -j4

# Copy built libui things to ui-dependency dirs
cp out/libui.* ../../deps/lib && cp ../ui*.h ../../deps/include

# Prepare and build ui
cd ../..
phpize && ./configure --with-ui=deps
make && make EXTENSION_DIR="${EXT_DIR}" install && echo "extension=${EXT_DIR}/ui.so" >> ${INI_TARGET}

# Return if building ui worked
exit $?
