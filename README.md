#A minimalist, dynamic, tiled layout generator

## About

Mozambique is an experiment in automating layouting. The big idea is to allow
the application engine to construct page layouts, allowig authors and admins
to work on more interesting issues.

## Documentation
In general the EKindMozambique Application Component acts as a facade to 
Mozambique functionality.


### Development
Developers can find an extensive list of Interfaces under /interfaces. Code is 
generally well documented "on site" with Javadoc blocks. Implementor public 
interfaces are only documented if the deviate from the interface documentation.

## Usage
You have to configure the mozambique component in the Yii application's main
config file.

```
'components'=>array(
    ...
    'mozambique'=>array(
        'class'=> "application.extensions.kindMozambique.EKindMozambique",
        'finderAlias'=> "application.alias.to.IMozambiqueFinder.implementation"
    ),
    ...
)
```

The class and finder properties are mandatory. Check out EKindMozambique for
more properties You can manipulate (mostly implementations of speciffic internal 
components).

Then You can use Mozambique to render content automatically (by invoking the 
finder's methods without arguments).
```
    echo Yii::app()->mozambique->renderWidget();
```
OR

```
    echo Yii::app()->mozambique->renderWidgetWithItems($items);
```
To render speciffic content.