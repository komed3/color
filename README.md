# Color

This class provides many functions for converting color spaces, calculates color differences, scales and much more.

## Documentation

### Set new color functions

#### ``setRGB( r, g, b )``

Defines color from parameters ``red``, ``green`` and ``blue`` [0...255].

#### ``setRYB( r, y, b )``

Defines color from parameters ``red``, ``yellow`` and ``blue`` [0...255].

#### ``setHEX( hex )``

Defines color from hexadecimal notation ``hex`` (3 or 6 digits, with or without hash).

#### ``setHSL( h, s, l )``

Defines color from parameters ``hue`` [0...360], ``saturnation`` [0...1] and ``lightness`` [0...1].

#### ``setHSV( h, s, v )``

Defines color from parameters ``hue`` [0...360], ``saturnation`` [0...1] and ``value`` [0...1].

#### ``setCMYK( c, m, y, k )``

Defines color from parameters ``cyan``, ``magenta``, ``yellow`` and ``black key`` [0...1].

#### ``setYUV( y, u, v )``

Defines color from luma component ``y`` and chrominance components, called ``u`` (blue projection) and ``v`` (red projection) [0...255].

### Output functions

#### ``toRGB()``

Returns color as ``[ r, g, b ]``.

#### ``toRYB()``

Returns color as ``[ r, y, b ]``.

#### ``toCMYK()``

Returns color as ``[ c, m, y, k ]``.

#### ``toHEX( hash [ = true ] )``

Returns color with hexadecimal notation.

#### ``toHSL()``

Returns color as ``[ h, s, l ]``.

#### ``toHSV()``

Returns color as ``[ h, s, v ]``.

#### ``toXYZ()``

Returns color as ``[ x, y, z ]``.

#### ``toYxy()``

Returns color as ``[ Y, x, y ]``.

#### ``toLAB()``

Returns color as ``[ l, a, b ]``.

#### ``toYUV()``

Returns color as ``[ y, u, v ]``.

#### ``toYIQ()``

Returns color as ``[ y, i, q ]``.

#### ``toYCbCr()``

Returns color as ``[ y, Cb, Cr ]``.

#### ``toYPbPr()``

Returns color as ``[ y, Pb, Pr ]``.

### Calculation functions

#### ``gamma()``

Returns ``[ r, g, b ]`` with gamma correction.

#### ``deltaE( Color compare, percentage [ = false ] )``

Returns __∆E__ as the difference or distance between two colors (CIE76).

| ΔE        | Evaluation                                       |
| --------- | ------------------------------------------------ |
| 0.0...0.5 | almost imperceptible                             |
| 0.5...1.0 | noticeable to the trained eye                    |
| 1.0...2.0 | slight color difference                          |
| 2.0...4.0 | perceived color difference                       |
| 4.0...5.0 | significant, rarely tolerated color difference   |
| above 5.0 | the difference is evaluated as a different color |

#### ``diff( Color compare, space [ = 'RGB' ] )``

Returns difference or distance between two colors. Accepted color spaces are ``RGB`` and ``RYB``.

#### ``match( Color compare, space [ = 'RGB' ] )``

Returns difference or distance between two colors at percentage value. Accepted color spaces are ``RGB`` and ``RYB``.

#### ``complementary()`` or ``invert()``

Returns ``Color`` object with complementary color.

#### ``lighten( value )``

Return ``Color`` object lightened by ``value`` [0...1]

#### ``darken( value )``

Return ``Color`` object darkened by ``value`` [0...1]

#### ``triadic()`` or ``triplet()``

Returns the triadic color scheme (three colors that have the same distance from each other in the color wheel).

#### ``contrast()``

Returns contrast of given color: ``0`` is dark and ``1`` is light contrast.

## Examples

### Example 1: Convert color

```php

# define new Color object
$color = new Color();

# set RGB color
$color->setRGB( 136, 176, 75 );

# convert to HEX
print_r( $color->toHEX() );

# convert to CMYK
print_r( $color->toCMYK() );
```

##### Output:

```html

#88b04b

Array
(
    [c] => 0.22727272727273
    [m] => 0
    [y] => 0.57386363636364
    [k] => 0.30980392156863
)
```

### Example 2: Distance between colors

```php

# define new Color objects and set colors
$color_1 = ( new Color() )->setHEX( '#34568B' );
$color_2 = ( new Color() )->setHSL( 257, 0.242, 0.471 );

# get ΔE
print_r( $color_1->deltaE( $color_2 ) );

# get color match
print_r( $color_1->match( $color_2 ) );
```

##### Output:

```html

16.369734508952

0.87070712531894
```
