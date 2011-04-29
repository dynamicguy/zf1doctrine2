#!/bin/sh

DIR=`php -r "echo dirname(dirname(realpath('$0')));"`
LIB="$DIR/library"
DOCTRINE="$DIR/library/Doctrine"
VERSION=`cat "$DIR/VERSION"`

# initialization
if [ "$1" = "--reinstall" -o "$2" = "--reinstall" ]; then
    rm -rf $DOCTRINE
fi

mkdir -p "$DOCTRINE" && cd "$DOCTRINE"

##
# @param destination directory (e.g. "doctrine")
# @param URL of the git remote (e.g. http://github.com/doctrine/doctrine2.git)
# @param revision to point the head (e.g. origin/HEAD)
#
install_git()
{
    INSTALL_DIR=$1
    SOURCE_URL=$2

    echo "> Installing/Updating " $INSTALL_DIR

    if [ ! -d $INSTALL_DIR ]; then
        git clone $CLONE_OPTIONS $SOURCE_URL $INSTALL_DIR
    fi

    cd $INSTALL_DIR
    git fetch origin
    git reset --hard
    cd ..
}

# Symfony
#install_git Symfony http://github.com/symfony/symfony.git #v$VERSION

# Doctrine ORM
#install_git Symfony http://github.com/doctrine/doctrine2.git

# Doctrine DBAL
#install_git Symfony-dbal http://github.com/doctrine/dbal.git

# Doctrine Common
#install_git Common http://github.com/doctrine/common.git


