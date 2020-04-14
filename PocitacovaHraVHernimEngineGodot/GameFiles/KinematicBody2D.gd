extends KinematicBody2D

const UP = Vector2(0, -1)
const DOWN = 20
const JUMP = 300
const MAX_SPEED = 180
const ACCELERATION = 50
const FIREBALL = preload("res://Fireball.tscn")

var motion = Vector2()
var attack = false
var double_jump = 0
var is_on_ground = true
var anim_finish = 0
var shot = true
var is_dead = false

func _physics_process(delta):
	if is_dead == false:
		motion.y += DOWN
		var friction = false
		
		if Input.is_action_pressed("ui_right"):
			if attack == false:
				motion.x = min(motion.x+ACCELERATION, MAX_SPEED)
				$Sprite.play("Run")
				$Sprite.set_speed_scale(1.2)
				$Sprite.flip_h = false
				if sign($Position2D.position.x) == -1:
					$Position2D.position.x *= -1
		elif Input.is_action_pressed("ui_left"):
			if attack == false:
				motion.x = max(motion.x-ACCELERATION, -MAX_SPEED)
				$Sprite.play("Run")
				$Sprite.set_speed_scale(1.2)
				$Sprite.flip_h = true
				if sign($Position2D.position.x) == +1:
					$Position2D.position.x *= -1
			
		else:
			if is_on_ground == true && attack == false:
				motion.x = lerp(motion.x, 0, 0.2)
				friction = true
				$Sprite.play("Idle")
				$Sprite.set_speed_scale(1.2)
			
		if Input.is_action_just_pressed("ui_up"):
			if attack == false:
				if double_jump < 1:
					double_jump += 1
					motion.y = -JUMP
					is_on_ground = true
			
		if is_on_floor():
			is_on_ground = true
			double_jump = 0
		else:
			if attack == false:
				is_on_ground = false
				if motion.y < 0:
					$Sprite.play("Idle")
				else:
					$Sprite.play("Idle")
				
		
		if Input.is_action_just_pressed("alt") && attack == false && is_on_ground == true:
			motion.x = 0
			attack = true
			shot = false
			$Sprite.play("Fireball")
			$Sprite.set_speed_scale(1.6)
			
		
		
		motion = move_and_slide(motion, UP)
		pass
		
		if get_slide_count() > 0:
				for i in range(get_slide_count()):
					if "enemy" in get_slide_collision(i).collider.name:
						dead()
		
func dead():
	is_dead = true
	motion = Vector2(0, 0)
	$Sprite.play("death")
	$Sprite.set_speed_scale(3)
	$CollisionShape2D.disabled = true
	$Timer.start()

func _on_Sprite_animation_finished():
	if shot == false:
		shot = true
		var fireball = FIREBALL.instance()
		if sign($Position2D.position.x) == 1:
			fireball.set_fireball_direction(1)
			get_parent().add_child(fireball)
			fireball.position = $Position2D.global_position
		else:
			fireball.set_fireball_direction(-1)
			get_parent().add_child(fireball)
			fireball.position = $Position2D.global_position
	attack = false
	


func _on_Timer_timeout():
	get_tree().change_scene("TitleScreen.tscn")
	
	

