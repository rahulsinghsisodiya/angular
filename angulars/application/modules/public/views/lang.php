<div style="height: 700px;padding-top: 100px;padding-left: 100px;">

    <div ng-app="myApp" ng-controller="myCtrl">

        First Name: <input type="text" ng-model="firstName"><br>
        Last Name: <input type="text" ng-model="lastName"><br>
        <br>
        Full Name: {{firstName + " " + lastName}}

        <div  ng-controller="hov">

            <h1 ng-mouseover="count = count + 1">Mouser</h1>
            <h2>{{ count}}</h2>

        </div>
        <div ng-controller="addproduct">
            <ul>
                <li ng-repeat=" x in products track by $index">{{x}}</li>
            </ul>
            <input ng-model="addMe">
            <button ng-click="addItem()">Add</button>
            <div class="success">{{message}}</div>

        </div>


    </div>
</div>
