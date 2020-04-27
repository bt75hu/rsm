#! /bin/bash


sudo mount -t cifs //192.168.66.249/www ./adaskornyezet -o username=html,password=Radio123456,iocharset=utf8,file_mode=0775,dir_mode=0775,noauto,exec,rw 2>&1
