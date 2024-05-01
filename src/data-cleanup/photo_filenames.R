# Get list of all photos

library(tidyverse)
library(stringr)

`%like%` <- function(x,y) grepl(y, x)
`%notin%` <- negate(`%in%`)

# Crawl through the photo directories and get + clean all photo filenames
schools <- list.files('data/NCAA')

filenames <- NULL

for (uni in schools) {
  
  photo_files <- list.files(paste0('data/NCAA/', uni))
  
  temp <- tibble(
    school = uni,
    filename = photo_files
  )
  
  filenames <- filenames |> 
    bind_rows(temp)
  
}

filenames <- filenames |> 
  filter(filename %notin% c('Colors', 'Cropped', 'Demography', 'Reports', 'RGB', 'Skin Tones')) |> 
  mutate(
    filename_cleaned = case_when(
      str_detect(filename, ' Jr\\.') ~ str_replace_all(filename, ' Jr\\.', ' Jr'),
      str_detect(filename, ' Sr\\.') ~ str_replace_all(filename, ' Sr\\.', ' Sr'),
      str_detect(filename, ' St\\.') ~ str_replace_all(filename, ' St\\.', ' St'),
      str_detect(filename, 'St\\.') ~ str_replace_all(filename, 'St\\.', 'St'),
      TRUE ~ filename
    )
    ) |> 
  mutate(
    number = str_split(filename_cleaned, "\\.") |> 
      map_chr(~ paste0(.[1], collapse = ".")),
    last_name = str_split(filename_cleaned, "\\.") |> 
      map_chr(~ paste0(.[2], collapse = ".")),
    position = str_split(filename_cleaned, "\\.") |> 
      map_chr(~ paste0(.[3], collapse = ".")),
    fn_uni = str_split(filename_cleaned, "\\.") |> 
      map_chr(~ paste0(.[4], collapse = "."))
         )

# Write out all filenames
write.csv(
  file = 'photo_filenames.csv',
  filenames
)

# Write out file of cases where the folder spelling of the uni name is different
# from the photo filename spelling of the uni name
#
# Note that some Baylor photos are misplaced in the Cincinnati folder: account
# for this later when matching
write.csv(
  file = 'uni_crosswalk.csv',
  filenames |> 
  filter(school != fn_uni) |> 
  distinct(school, fn_uni) |> 
    rename(folder_uni = school,
           filename_uni = fn_uni)
  )
