
<style>
 
 
.collapsebox { 
    display : block;
    height:auto !important; 
    margin-bottom:2rem;
}
.collapse-content {
    display:none; 
    float: left;
    min-width:100%;
    margin-bottom:1rem;
}
.vision {
    display: none; 
}
.hider:target + .vision {
    display: inline; 
     margin-bottom:2em;
}
.hider:target {
    display: none; 
}
.hider:target ~ .collapse-content{
    display:inline; 
    margin-bottom:2em;
}

/*style the (+) and (-) */
.hider, .vision {
	width: 100%;
	height: 35px;
	border-radius: 2px;
	text-transform:uppercase;
	color: #fff;
	text-align: center;
	vertical-align: middle;
	text-decoration: none;
	background: #4bcf23;
	opacity: .95;
	float: left;
	padding-top: 7px;
	margin-bottom:1rem;
}


 
</style>