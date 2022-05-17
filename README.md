# School System API

## <center>API ROUTES </center>

<br>
<br>

### ~ &nbsp; `/school`

<br>



>  #### GET &nbsp; `"/school"` 
- ##### `Parameter`: null

- ##### `Body` : null

- ##### `Response` : array of schools

<br>

>  #### GET &nbsp; `"/school/{schoolID}"` 
- ##### `Parameter`: schoolID

- ##### `Body` : null

- ##### `Response` : specific school

<br>

> #### POST &nbsp;  `"/school"`

- ##### ` Parameter ` : null

- ##### `Body`: schoolName, schoolEmail, password, plan, schoolDomain, schoolID, schoolSlogan.

- ##### `Required Body`: schoolName, schoolEmail, password.

- ##### `Headers`: Accept -> "application/json"

<br>

> #### PUT  &nbsp; `"/school"/{schoolID}`

- ##### `Parameter`: schoolID.

- ##### `Body`: schoolName, schoolEmail, password, plan, schoolDomain.

- ##### `Headers`: Accept -> "application/json"

<br>

> #### PATCH &nbsp; `"/school"/{schoolID}`

- ##### `Parameter`: schoolID.

- ##### `Body`: schoolName, schoolEmail, schoolSlogan, password, plan, schoolDomain.

- ##### `Headers`: Accept -> "application/json"

<br>

> #### DELETE &nbsp; `"/school"/{schoolID}`

- ##### `Parameter`: schoolID.

- ##### `Body`: null

- ##### `Headers`: Accept -> "application/json"



<br>
<br>

### ~ &nbsp; `/session`

<br>



>  #### GET &nbsp; `"/session"` 
- ##### `Parameter`: null

- ##### `Body` : null

- ##### `Response` : array of sessions

<br>

>  #### GET &nbsp; `"/session/{sessionId}"` 
- ##### `Parameter`: schoolID

- ##### `Body` : null

- ##### `Response` : specific school

<br>

> #### POST &nbsp;  `"/session"`

- ##### ` Parameter ` : null

- ##### `Body`: session, is_active

- ##### `Required Body`: session

- ##### `Headers`: Accept -> "application/json"

<br>

> #### PUT  &nbsp; `"/session"/{sessionId}`

- ##### `Parameter`: sessionId.

- ##### `Body`: schoolName, is_active

- ##### `Headers`: Accept -> "application/json"

<br>

> #### PATCH &nbsp; `"/session"/{sessionId}`

- ##### `Parameter`: sessionId.

- ##### `Body`: session, is_active

- ##### `Headers`: Accept -> "application/json"

<br>

> #### DELETE &nbsp; `"/session"/{sessionId}`

- ##### `Parameter`: sessionId.

- ##### `Body`: null

- ##### `Headers`: Accept -> "application/json"

<br>
<br>
