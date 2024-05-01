#####
# Matching

library(tidyverse)
library(janitor)
library(readxl)

#####
# Clean deduped player dataˆ

players_data_final_deduped <- read_csv("players_data_final_deduped.csv")

players_data_final_deduped <- players_data_final_deduped |> 
  mutate(
    last_name_cleaned = case_when(
      str_detect(`last name`, ' Jr\\.') ~ str_replace_all(`last name`, ' Jr\\.', ' Jr'),
      str_detect(`last name`, ' Sr\\.') ~ str_replace_all(`last name`, ' Sr\\.', ' Sr'),
      str_detect(`last name`, ' St\\.') ~ str_replace_all(`last name`, ' St\\.', ' St'),
      str_detect(`last name`, 'St\\.') ~ str_replace_all(`last name`, 'St\\.', 'St'),
      TRUE ~ `last name`
    ),
    university = case_when(
      str_detect(university, 'State Spartans') ~ 'San Jose State Spartans',
      TRUE ~ university
    )
  ) |> 
  clean_names()

players_deduped_with_count <- players_data_final_deduped |> 
  group_by(number, last_name, university, position) |> 
  summarise(rows = n())

#####
# Join photo filenames using crosswalk

uni_name_crosswalk <- read_excel("data/uni_name_crosswalk.xlsx")
photo_filenames <- read_csv("photo_filenames.csv")

# Remove cases where photos are misplaced
# (e.g., Baylor photos in Cincinnati folder)
# and "fix" San Jose State
photo_filenames <- photo_filenames |> 
  filter(!(school == 'Cincinnati' & fn_uni == 'Baylor')) |> 
  mutate(
    fn_uni = case_when(
      str_detect(fn_uni, 'San José State') ~ 'San Jose State',
    TRUE ~ fn_uni
    )
  )

players_data_final_deduped <- players_data_final_deduped |> 
  left_join(uni_name_crosswalk |> 
              select(player_data_uni, photo_uni),
            by = c('university' = 'player_data_uni'))

players_data_final_deduped_joined_matched <- players_data_final_deduped |> 
  mutate(key = paste0(number, last_name_cleaned, position, photo_uni)) |> 
  left_join(
    photo_filenames |> 
      mutate(key = paste0(number, last_name, position, fn_uni)) |> 
      select(filename,
             key),
    by = 'key'
  )

temp <- players_data_final_deduped |> 
  filter(university == 'San Jose State Spartans')

temp <- players_data_final_deduped_joined_matched |> 
  filter(university == 'San Jose State Spartans')

write.csv(file = 'players_data_matched_ib.csv', players_data_final_deduped_joined_matched)

# Check the duplicated records: these should all be SMU players
duplicates <- players_data_final_deduped_joined_matched |> 
  group_by(key, last_name, last_name_cleaned, university) |> 
  summarise(row_count = n()) |> 
  filter(row_count > 1)
