### Install and How to use
- install vagrant and composer
- unzip worldfirst_test_archive 
- open terminal / iterm and go to worldfirst_test_archive directory
- execute command:    
    -     composer install
- execute command: 
    -     vagrant up
- execute command: 
    -     vagrant ssh 
- execute command: 
    -     cd code
- Test source code: 
    -     ./phpunit test
- CLI App: 
    -     ./wfCLI 

----
### DB Client Info: 
- IP: 127.0.0.1
- Port: 33060
- Login: homestead
- PW: secret
- DB: wordfirst

----
### Supported Command 
        /home/vagrant/code >./wfCLI Account:
-  Account:Balance          : Display Account Balance command
-  Account:Close            : Close Account command
-  Account:Deposit          : Deposit Account command
-  Account:Info             : Display Account Info command
-  Account:ListTransaction  : Deposit Account command
-  Account:Open             : Open Account command
-  Account:Withdrawal       : Withdrawal Account command

----
### Not handled feature or use case:🙉
- not handle overdraft facility
- not handle concurrent transaction for withdrawal, so negative balance may happen.
- not handle security/permission for CLI app
- implemented delete function for auto test which is not necessary and risky in real world.

----
### Supported Test 
        /home/vagrant/code > phpunit test
- HelperTest for test validator helper function (test/HelperTest.php)
    - validate PDO object
    - validate Email address
    - validate user name 
- AccountCommandTest for test account command. (test/AccountCommandTest.php)
    - Use Case: open account
    - Use Case: close account
    - Use Case: Deposit Account
    - Use Case: Withdrawal Account
    - Use Case: List Account Transaction
    - Use Case: Account Balance
