#### Parameters

$keyvaultname = "ClusterKV"
$location = "uksouth"
$keyvaultrg = "Demo-RG"
$sshkeysecret = "akspubkey"
$spnclientid = "d62f1906-f00d-4cf3-a0ff-6a862ab40f43"
$clientidkvsecretname = "Automation-SP"
$spnclientsecret = "9w88Q~J2opMru3_HQa3EI-Gzm-Nh.HXdx2O0faeA"
$spnkvsecretname = ""
$spobjectID = "c0a3017d-c5ee-4c23-bd37-55f8425a8620"
$userobjectid = "8023917c-ebd5-4d12-8e27-7b0914506e36"


#### Create Key Vault

New-AzResourceGroup -Name $keyvaultrg -Location $location

New-AzKeyVault -Name $keyvaultname -ResourceGroupName $keyvaultrg -Location $location

Set-AzKeyVaultAccessPolicy -VaultName $keyvaultname -UserPrincipalName $userobjectid -PermissionsToSecrets get,set,delete,list

#### create an ssh key for setting up password-less login between agent nodes.

ssh-keygen  -f ~/.ssh/id_rsa_terraform


#### Add SSH Key in Azure Key vault secret

$pubkey = cat ~/.ssh/id_rsa_terraform.pub

$Secret = ConvertTo-SecureString -String $pubkey -AsPlainText -Force

Set-AzKeyVaultSecret -VaultName $keyvaultname -Name $sshkeysecret -SecretValue $Secret


#### Store service principal Client id in Azure KeyVault

$Secret = ConvertTo-SecureString -String $spnclientid -AsPlainText -Force

Set-AzKeyVaultSecret -VaultName $keyvaultname -Name $clientidkvsecretname -SecretValue $Secret


#### Store service principal Secret in Azure KeyVault

$Secret = ConvertTo-SecureString -String $spnclientsecret -AsPlainText -Force

Set-AzKeyVaultSecret -VaultName $keyvaultname -Name $spnkvsecretname -SecretValue $Secret


#### Provide Keyvault secret access to SPN using Keyvault access policy

Set-AzKeyVaultAccessPolicy -VaultName $keyvaultname -ServicePrincipalName $spobjectID -PermissionsToSecrets Get,Set
