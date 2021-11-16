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
        
        public function toHex(
            bool $hash = true
        ) {
            
            return ( $hash ? '#' : '' ) . implode( '',
                array_map( function ( $val ) {
                    return dechex( $val );
                }, $this->color )
            );
            
        }
        
        public function getColor() {
            
            return $this->color;
            
        }
        
    }
    
?>
