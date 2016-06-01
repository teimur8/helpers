//install
	https://atlas.hashicorp.com/laravel/boxes/homestead

	vagrant init laravel/homestead
		vagrant up --provider virtualbox
			vagrant ssh 

	'/cygdrive/c/Program Files/Oracle/VirtualBox/VBoxManage.exe' list runningvms  // запущенные машины
	'/cygdrive/c/Program Files/Oracle/VirtualBox/VBoxManage.exe' list vms   // установленные машины


// sharing files
	
	Если положить фаил в папку с vagrantfile, то на машине он будет доступен в /vagrant

	sudo ln -s /vagrant/www /usr/share/nginx/www   // создаем симовличекую ссылку

// VagrantFile

	//gui interface
		// чет не пашет
		config.vm.provider "virtualbox" do |vb|
		    # Display the VirtualBox GUI when booting the machine
		    vb.gui = true
		  
		    # Customize the amount of memory on the VM:
		    vb.memory = "1024"
	  	end

	// comands 
		vagrant reload 
		vagrant suspend  	// отключаем с сохранением состояния
			vagrant resume	// восстанавливаем
		vagrant halt 		// отключаем как комп
		vagrant destroy   	// удаляет машину с диска

	// provision автоматическая установка пакетов при создании машины

		config.vm.provision 'shell', path: 'provision.sh'
			// provision.sh  в этом фаиле прописываем команды
				apt-get -y update
				apt-get -y install nginx
				service nginx start

		vagrant provision	// запускаем принудительно

	// доступ из вне

  		config.vm.network 'forwarded_port', guest: 80, host: 8080, id:'nginx' // снаружи localhost:8080
  			// доп опции
  				auto_correct: true // автоматом выбирает порт при запуске нескольких машин port collisons
  				host_ip: "X.X.X.X"




