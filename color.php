<?php
    
    class Color {
        
        private $color;
        
        public function setHexColor(
            string $color
        ) {
            
            $color = str_replace( '#', '', $color );
            $spl = strlen( $color ) / 3;
            $rep = 3 - $spl;
            
            $this->color = array_map( function ( $val ) use ( $rep ) {
                return hexdec( str_repeat( $val, $rep ) );
            }, str_split( $color, $spl ) );
            
        }
        
        public function setRGBColor(
            array $color
        ) {
            
            $this->color = array_slice( $color, 0, 3 );
            
        }
        
        public function toRGB(
            bool $assoc = true
        ) {
            
            return $assoc ? [
                'r' => $this->color[0],
                'g' => $this->color[1],
                'b' => $this->color[2]
            ] : $this->color;
            
        }
        
        public function toHex(
            bool $hash = true
        ) {
            
            return ( $hash ? '#' : '' ) . implode( '',
                array_map( function ( $val ) {
                    return dechex( $val );
                }, $this->color )
            );
            
        }
        
        public function toHSL() {
            
            list( $r, $g, $b ) = array_map( function ( $val ) {
                return $val / 255;
            }, $this->color );
            
            $max = max( $r, $g, $b );
            $min = min( $r, $g, $b );
            
            $delta = $max - $min;
            
            $lightness = ( $max + $min ) / 2;
            
            if( $delta == 0 ) {
                
                $saturation = $hue = 0;
                
            } else {
                
                $saturation = $delta / ( 1 - abs( 2 * $lightness - 1 ) );
                
                switch( $max ) {
                    
                    case $r:
                        $hue = 60 * fmod( ( $g - $b ) / $delta, 6 ); 
                        if( $b > $g ) $h += 360;
                        break;
                    
                    case $g: 
                        $hue = 60 * ( ( $b - $r ) / $delta + 2 ); 
                        break;
                    
                    case $b: 
                        $hue = 60 * ( ( $r - $g ) / $delta + 4 ); 
                        break;
                    
                }
                
            }
            
            return [
                'h' => $hue,
                's' => $saturation,
                'l' => $lightness
            ];
            
        }
        
        public function getColor() {
            
            return $this->color;
            
        }
        
    }
    
?>
