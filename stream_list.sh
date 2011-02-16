#!/bin/bash
cvlc 'http://iptv.bg/asx/btc/high/b3f2c76045df3944d1eac83db26684c8/kanal1.asx' --sout '#standard{access=http,mux=asf,dst=:800/kanal1.asx}' &
cvlc 'http://iptv.bg/asx/btc/high/b3f2c76045df3944d1eac83db26684c8/btv.asx' --sout '#standard{access=http,mux=asf,dst=:801/btv.asx}' &
cvlc 'http://iptv.bg/asx/btc/high/b3f2c76045df3944d1eac83db26684c8/gtv.asx' --sout '#standard{access=http,mux=asf,dst=:802/gtv.asx}' &
cvlc 'http://iptv.bg/asx/btc/high/b3f2c76045df3944d1eac83db26684c8/tv7.asx' --sout '#standard{access=http,mux=asf,dst=:803/tv7.asx}' &
