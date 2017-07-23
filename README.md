# Subcontractors log/payment
Personal try on HTML, CSS, Javascript, PHP and MySql
## 1- Introduction
This software is an automation of the payment issuance/tracking of subcontract-
ors in a construction project.
## 2- Stored information and available reports
The software at this stage is supposed to do the following:
1. Have a list of subcontractors and their respective subcontracts
2. List of bonds to each subcontract
3. List of payments certificate to each subcontract
4. List of bank transactions to each certificate/subcontract/subcontractor

## 3-Database

The database has the following tables:

| Sn | Tables_in_db							|
|----|--------------------------------------|
| 1  | subcontractors						|
| 2  | subcontracts							|
| 3  | bonds								|
| 4  | subcontractor_payment_cert			|
| 5  | subcontractor_payment_transaction	|
| 6  | users								|




### 1- subcontractors

| Field            | Type                 | Null | Key | Default | Extra |
|------------------|----------------------|------|-----|---------|-------|
| subcontractor_id | smallint(6) unsigned | NO   | PRI | NULL    |       |
| name             | varchar(100)         | NO   | UNI | NULL    |       |
| ScBSCode         | varchar(8)           | YES  | UNI | NULL    |       |


### 2- subcontracts


| Field             | Type                 | Null | Key | Default | Extra |
|-------------------|----------------------|------|-----|---------|-------|
| subcontract_id    | smallint(5) unsigned | NO   | PRI | NULL    |       |
| subcontractor_id  | smallint(5) unsigned | YES  | MUL | NULL    |       |
| subcontract_scope | varchar(100)         | YES  |     | NULL    |       |
| active            | tinyint(1)           | YES  |     | NULL    |       |
| SCA_Amount        | decimal(9,3)         | YES  |     | NULL    |       |
| SCA_Ref           | varchar(50)          | YES  |     | NULL    |       |
| remarks           | varchar(50)          | YES  |     | NULL    |       |


### 3- bonds

| Field          | Type                   | Null | Key | Default | Extra |
|----------------|------------------------|------|-----|---------|-------|
| bond_id        | int(10) unsigned       | NO   | PRI | NULL    |       |
| bond_ref       | varchar(50)            | NO   |     | NULL    |       |
| subcontract_id | int(10) unsigned       | NO   |     | NULL    |       |
| bond_type      | varchar(50)            | NO   |     | NULL    |       |
| bond_purpose   | varchar(50)            | NO   |     | NULL    |       |
| issue_date     | date                   | NO   |     | NULL    |       |
| exp_date       | date                   | NO   |     | NULL    |       |
| issuing_bank   | varchar(50)            | NO   |     | NULL    |       |
| bond_value     | decimal(12,3) unsigned | NO   |     | NULL    |       |
| notes          | varchar(50)            | NO   |     | NULL    |       |


### 4- subcontractor_payment_cert

| Field          | Type                 | Null | Key | Default | Extra |
|----------------|----------------------|------|-----|---------|-------|
| cert_id        | smallint(5) unsigned | NO   | PRI | NULL    |       |
| subcontract_id | smallint(5) unsigned | YES  | MUL | NULL    |       |
| payment_sn     | smallint(5) unsigned | YES  |     | NULL    |       |
| payment_date   | date                 | YES  |     | NULL    |       |
| work_exec      | decimal(12,3)        | YES  |     | NULL    |       |
| ap             | decimal(12,3)        | YES  |     | NULL    |       |
| on_acc         | decimal(12,3)        | YES  |     | NULL    |       |
| variations     | decimal(12,3)        | YES  |     | NULL    |       |
| mos            | decimal(12,3)        | YES  |     | NULL    |       |
| ret            | decimal(12,3)        | YES  |     | NULL    |       |
| ret_rel        | decimal(12,3)        | YES  |     | NULL    |       |
| ap_rec         | decimal(12,3)        | YES  |     | NULL    |       |
| used_mos       | decimal(12,3)        | YES  |     | NULL    |       |
| deductions     | decimal(12,3)        | YES  |     | NULL    |       |
| prev_paid      | decimal(12,3)        | YES  |     | NULL    |       |
| amount         | decimal(12,3)        | YES  |     | NULL    |       |
| remarks        | varchar(50)          | YES  |     | NULL    |       |


### 5- subcontractor_payment_transaction


| Field        | Type                 | Null | Key | Default | Extra |
|--------------|----------------------|------|-----|---------|-------|
| trans_id     | smallint(5) unsigned | NO   | PRI | NULL    |       |
| cert_id      | smallint(5) unsigned | YES  | MUL | NULL    |       |
| trans_no     | varchar(30)          | YES  |     | NULL    |       |
| trans_date   | date                 | YES  |     | NULL    |       |
| trans_amount | decimal(12,3)        | YES  |     | NULL    |       |
| remarks      | varchar(50)          | YES  |     | NULL    |       |



### 6- users


| Field    | Type         | Null | Key | Default | Extra          |
|----------|--------------|------|-----|---------|----------------|
| userId   | int(11)      | NO   | PRI | NULL    | auto_increment |
| userName | varchar(30)  | NO   |     | NULL    |                |
| userPass | varchar(255) | NO   |     | NULL    |                |

## 4- Code files
I might describe here what does each file do
