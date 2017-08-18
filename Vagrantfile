Vagrant.configure("2") do |config|
  config.vm.box = "puphpet/ubuntu1604-x64"

  config.vm.provision "shell", inline: <<-SHELL
    add-apt-repository ppa:ondrej/php
    apt-get update
    apt-get install -y php7.1 php7.1-xml php7.1-mbstring php7.1-zip php7.1-curl composer
  SHELL
end
