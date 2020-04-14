extends KinematicBody2D

onready var Player = get_parent().get_node("player")

var vel = Vector2(0, 0)

var grav = 1800
var max_grav = 3000

var react_time = 400
var dir = 0
var next_dir = 0
var next_dir_time = 0

var next_jump_time = -1

var target_player_dist = 1

var eye_reach = 90
var vision = 200

var is_dead = false
var health = 2

func _ready():
	set_process(true)

func set_dir(target_dir):
	if is_dead == false:
		if next_dir != target_dir:
			next_dir = target_dir
			next_dir_time = OS.get_ticks_msec() + react_time

func sees_player():
	if is_dead == false:
		var eye_center = get_global_position()
		var eye_top = eye_center + Vector2(0, -eye_reach)
		var eye_left = eye_center + Vector2(-eye_reach, 0)
		var eye_right = eye_center + Vector2(eye_reach, 0)
	
		var player_pos = Player.get_global_position()
		var player_extents = Player.get_node("CollisionShape2D").shape.extents - Vector2(1, 1)
		var top_left = player_pos + Vector2(-player_extents.x, -player_extents.y)
		var top_right = player_pos + Vector2(player_extents.x, -player_extents.y)
		var bottom_left = player_pos + Vector2(-player_extents.x, player_extents.y)
		var bottom_right = player_pos + Vector2(player_extents.x, player_extents.y)
	
		var space_state = get_world_2d().direct_space_state
	
		for eye in [eye_center, eye_top, eye_left, eye_right]:
			for corner in [top_left, top_right, bottom_left, bottom_right]:
				if (corner - eye).length() > vision:
					continue
				var collision = space_state.intersect_ray(eye, corner, [], 1) # collision mask = sum of 2^(collision layers) - e.g 2^0 + 2^3 = 9
				if collision and collision.collider.name == "player":
					return true
		return false

func _process(delta):
	if is_dead == false:
		if Player.position.x < position.x - target_player_dist and sees_player():
			set_dir(-1)
			$Sprite.play("run")
			$Sprite.set_speed_scale(1.6)
			$Sprite.flip_h = true
		elif Player.position.x > position.x + target_player_dist and sees_player():
			set_dir(1)
			$Sprite.play("run")
			$Sprite.set_speed_scale(1.6)
			$Sprite.flip_h = false
		else:
			set_dir(0)
			$Sprite.play("Idle")
			$Sprite.set_speed_scale(1.2)
	
		if OS.get_ticks_msec() > next_dir_time:
			dir = next_dir
	
		if OS.get_ticks_msec() > next_jump_time and next_jump_time != -1 and is_on_floor():
			if Player.position.y < position.y - 64 and sees_player():
				vel.y = -600
			next_jump_time = -1
	
		vel.x = dir * 120
	
		if Player.position.y < position.y - 64 and next_jump_time == -1 and sees_player():
			next_jump_time = OS.get_ticks_msec() + react_time
	
		vel.y += grav * delta;
		if vel.y > max_grav:
			vel.y = max_grav
	
		if is_on_floor() and vel.y > 0:
			vel.y = 0
	
		vel = move_and_slide(vel, Vector2(0, -1))
		
		if get_slide_count() > 0:
				for i in range(get_slide_count()):
					if "Fireball" in get_slide_collision(i).collider.name:
						dead()
						print("Fireball")
					if "player" in get_slide_collision(i).collider.name:
						get_slide_collision(i).collider.dead()
		
func dead():
	health -= 1
	if health <= 0:
		$CollisionShape2D.disabled = true
		is_dead = true
		vel = Vector2(0, 0)
		$Sprite.play("death")
		$Sprite.set_speed_scale(3)
		$Timer.start()
					
					
					


func _on_Timer_timeout():
	queue_free()
