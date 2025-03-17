# AtlasNF - Sistema de Controle e Rastreamento de Notas fiscais

## Descrição
O **AtlasNF** é um sistema desenvolvido em PHP com MySQL, HTML, CSS E JAVASCRIPT, projetado para o gerenciamento de entregas de notas fiscais e de canhoto. Ele inclui funcionalidades de login, controle de relatórios e integração com banco de dados. O sistema é 
capaz de consultar dois arquivos .txt que possuem informações que serão utilizadas no index.html

# Instalação

## **Windows**

### **1. Instalar Dependências**
Antes de instalar o projeto, certifique-se de instalar os seguintes softwares:

- **XAMPP** (Apache e PHP, sem MySQL): [Baixar XAMPP](https://www.apachefriends.org/pt_br/download.html)
obs: instalei sem o MySQL, pois já possuia em minha máquina
- **MySQL Workbench**: [Baixar Workbench](https://dev.mysql.com/downloads/workbench/)
- **WinSCP** (Cliente FTP, opcional para conexões remotas): [Baixar WinSCP](https://winscp.net/eng/download.php)

### **2. Configuração do Ambiente**
1. Instale o **XAMPP**, mas **remova o MySQL incluído no pacote** caso já tenha uma instalação do MySQL independente.
2. Inicie os serviços **Apache** pelo painel de controle do XAMPP.
3. Copie a pasta do projeto `x` para o diretório de publicação do XAMPP:
   C:\xampp\htdocs\projeto
4. Acesse o phpMyAdmin pelo navegador: `http://localhost/phpmyadmin/` e importe o banco de dados (`AtlasNF.sql`).
obs: acessei diretamente pelo workbench e criei o banco de dados
5. Edite o arquivo de configuração do sistema (`config.php`) e ajuste as credenciais do banco de dados se necessário.
6. Acesse o sistema no navegador: `http://localhost/nome_projeto`

---

## **Linux (Ubuntu 22.04 Server)**

### **1. Instalar XAMPP**
Execute os seguintes comandos:

wget https://www.apachefriends.org/xampp-files/8.2.4/xampp-linux-x64-8.2.4-0-installer.run
chmod +x xampp-linux-x64-8.2.4-0-installer.run
sudo ./xampp-linux-x64-8.2.4-0-installer.run

Inicie o XAMPP:

sudo /opt/lampp/lampp start


### **2. Configurar Permissões**
Dê permissão para a pasta htdocs:

sudo chmod -R 777 /opt/lampp/htdocs
obs: conceda as permissões necessarias, pois o projeto grava as imagens em: assets/php/uploads
Copie o sistema para o diretório web:
/opt/lampp/htdocs/
obs: realizei a cópia pelo WinSCP (ver passo 4)

### **3. Criar Banco de Dados**
Inicie o MySQL e crie o banco:

sudo /opt/lampp/bin/mysql -u root -p
CREATE DATABASE nome_db;
USE nome_db;
a tabela está em CREATE_TABLE.txt


### **4. Criar Usuário FTP**

sudo adduser nome_user
sudo passwd senha_user
sudo usermod -d /opt/lampp/htdocs/projeto nome_user

Configure o FTP (caso necessário, instale o vsftpd):

sudo apt install vsftpd -y
sudo systemctl enable vsftpd
sudo systemctl start vsftpd


### **5. Acesso Externo ao Banco**
Edite o arquivo de configuração do MySQL:

sudo nano /opt/lampp/etc/my.cnf

Altere a linha:

bind-address = 0.0.0.0

Reinicie o MySQL:

sudo /opt/lampp/lampp restart

Agora o banco de dados pode ser acessado remotamente.

---

# **Acesso ao Sistema**
Para acessar o sistema, abra um navegador e digite:
- **Localmente**: `http://localhost/AtlasNF`
- **Remotamente**: `http://<IP_DO_SERVIDOR>/AtlasNF`



	