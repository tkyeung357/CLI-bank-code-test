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
- not handle CLI run on web browser
- not handle concurrent transaction for withdrawal, so negative balance may happen.
- not handle security/permission control for CLI app
- implemented delete function for auto test which is not necessary and risky in real world.

----
### Supported Test 
        /home/vagrant/code > phpunit test
- HelperTest for test validator helper function (test/HelperTest.php)
    - validate PDO object
    - validate Email address
    - validate user name 
- AccountCommandTest for test account command. (test/AccountCommandTest.php)
    - Use Case: open account:
        * Success case, open a test account
        * Failed case, try to open an account with existing email
        * Failed case, provide a invalid email.
    - Use Case: Deposit Account
        * Success case, deposit 100 to test account
        * Failed case, deposit "test amount" to test account
    - Use Case: Withdrawal Account
        * Success case, withdraw 100 to test account
        * Failed case: not enough money 
        * Failed case, deposit a invalid amount "test amount" to test account
    - Use Case: List Account Transaction
        * Success case
    - Use Case: Account Balance
        *  Success case
    - Use Case: close account
        * Success case, update account status to 0 (disabled)
        * Failed case, try to update a disabled account. no record udpated
        * Failed case, deposit closed account
        * Failed case, withdrawal closed account
    - Use Case: reopen account
        * Success case, update account status to 1 (enabled)

----
### DB Client Info: 
- IP: 127.0.0.1
- Port: 33060
- Login: homestead
- PW: secret
- DB: wordfirst
