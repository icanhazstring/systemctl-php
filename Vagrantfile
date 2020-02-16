Vagrant.configure("2") do |config|
  config.vm.box = "puphpet/ubuntu1604-x64"

  config.vm.provision "shell", inline: <<-SHELL
    add-apt-repository ppa:ondrej/php
    apt-get update
    apt-get install -y php7.2 php7.2-xml php7.2-mbstring php7.2-zip php7.2-curl php7.2-xdebug composer

    # Switch to /vagrant and install packages
    cd /vagrant && composer install
  SHELL
end
