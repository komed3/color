<?php
    
    class Color {
        
        private $color;
        
        private function isColor() {
            
            return is_array( $this->color ) && count( $this->color ) == 3;
            
        }
        
        public function setRGB(
            int $r = 0,
            int $g = 0,
            int $b = 0
        ) {
            
            $this->color = [ $r, $g, $b ];
            
        }
        
        public function setHEX(
            string $color
        ) {
            
            $color = str_replace( '#', '', $color );
            $spl = strlen( $color ) / 3;
            $rep = 3 - $spl;
            
            $this->color = array_map( function ( $val ) use ( $rep ) {
                return hexdec( str_repeat( $val, $rep ) );
            }, str_split( $color, $spl ) );
            
        }
        
        function setHSL(
            float $h = 0,
            float $s = 0,
            float $l = 0
        ) {
            
            $c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
            $x = $c * ( 1 - abs( fmod( $h / 60, 2 ) - 1 ) );
            $m = $l - ( $c / 2 );
            
            if( $h < 60 )
                $this->color = [ $c, $x, 0 ];
            
            else if( $h < 120 )
                $this->color = [ $x, $c, 0 ];
            
            else if( $h < 180 )
                $this->color = [ 0, $c, $x ];
            
            else if( $h < 240 )
                $this->color = [ 0, $x, $c ];
            
            else if( $h < 300 )
                $this->color = [ $x, 0, $c ];
            
            else
                $this->color = [ $c, 0, $x ];
            
            $this->color = array_map( function ( $val ) use ( $m ) {
                return ( $val + $m ) * 255;
            }, $this->color );
            
        }
        
        public function toRGB(
            bool $assoc = true
        ) {
            
            return $this->isColor() ? (
                $assoc ? [
                    'r' => $this->color[0],
                    'g' => $this->color[1],
                    'b' => $this->color[2]
                ] : $this->color
            ) : null;
            
        }
        
        public function toCMYK() {
            
            
            
        }
        
        public function toHEX(
            bool $hash = true
        ) {
            
            return $this->isColor() ? ( $hash ? '#' : '' ) . implode( '',
                array_map( function ( $val ) {
                    return str_pad( dechex( $val ), 2, '0', STR_PAD_LEFT );
                }, $this->color )
            ) : null;
            
        }
        
        public function toHSL() {
            
            if( !$this->isColor() )
                return null;
            
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
