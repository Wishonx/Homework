extends Area2D


const SPEED = 100
var motion = Vector2()
var direction = 1

var enemy_health = 0

func _ready():
	pass 
	

func set_fireball_direction(dir):
	direction = dir
	if dir == -1:
		$AnimatedSprite.flip_h = true

func _physics_process(delta):
	motion.x = SPEED * delta * direction
	translate(motion)
	$AnimatedSprite.play("Fireball")


func _on_VisibilityNotifier2D_screen_exited():
	queue_free()


func _on_Fireball_body_entered(body):
	if "enemy" in body.name:
		body.dead()
	queue_free()
	

	
	
	
