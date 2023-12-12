@import "compass/css3";

$height: 427px;
$width: 320px;

$light-blue: #288FBD;
$dark-blue: #005D8C;

@import url(https://fonts.googleapis.com/css?family=Open+Sans:300);

body {
  background-image: url(https://subtlepatterns.com/patterns/gplaypattern.png);
  background-position: center center;
  font-family: 'Open Sans', sans-serif;
}

div.business-card {
	height: $height;
  width: $width;
  margin-left: $width/-2;
  margin-top: $height/-2;
  
  position: absolute;
  top: 50%;
  left: 50%;
  
  perspective: 1000;
  
  &:hover .flipper, &.hover .flipper {
    transform: rotateY(180deg) rotateZ(90deg);
  }
}

div.flipper {
	transition: 0.6s;
	transform-style: preserve-3d;

	position: relative;
  transform-origin: center $width/2;
}

div.front, div.back {
  backface-visibility: hidden;

	position: absolute;
	top: 0;
	left: 0;
  height: $height;
  width: $width;
 
	box-shadow: 0 0 50px rgba(0,0,0,0.75);
}

div.front {
  background-color: white;
  z-index: 0;
  
  &:before, &:after {
    display: block;
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    border-width: $width/2;
    border-style: solid;
    border-color: transparent;
  }
  
  &:before {
    border-top-color: $light-blue;
    border-left-color: $light-blue;
    z-index: 2;
  }
  
  &:after {
    border-top-color: $dark-blue;
    border-right-color: $dark-blue;
  }
  
  div.name {
    position: absolute;
    bottom: ($height - $width - 90)/2;
    left: ($width - 150px) / 2;
    
    span {
      display: block;
      font-size: 40px;
      line-height: 45px;
    }
    
    span.first {
      color: $dark-blue;
    }
    
    span.last {
      color: $light-blue;
    }
    
    span.title {
      font-size: 20px;
      line-height: 20px;
    }
  }
}

div.back {
  background-color: $dark-blue;
  color: white;
  width: $height;
  height: $width;
  box-sizing: border-box;
  transform: rotateY(180deg) rotateZ(90deg);
  
  div.container-sm {
    float: left;
    width: 40%;
    height: 100%;
    position: relative;
  }
  
  div.container-lg {
    float: left;
    width: 60%;
    margin-top: 2rem;
  }
  
  figure.logo-white {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 120px;
    height: 120px;
    display: block;
    margin: -60px -60px;
  
    &:before, &:after {
      display: block;
      content: '';
      position: absolute;
      border-style: solid;
      border-color: transparent;
    }

    &:before {
      top: 0;
      left: 0;
      border-width: 60px;
      border-top-color: white;
      border-left-color: white;
      z-index: 2;
    }

    &:after {
      bottom: 0;
      right: 0;
      border-width: 56px;
      border-right-color: white;
    }
  }
 
  a {
    color: white;
    text-decoration: none;
    display: block;
    
    &:hover {
      text-decoration: underline;
    }
  }
  
  ul.social {
    font-size: 1.25rem;
    
    li {
      margin-top: 1rem;
      
      &:first-child {
        margin-top: 2rem;
      }
    }
  }
}