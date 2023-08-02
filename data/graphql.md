### Агрегация баллов за задания по занятиям и навыкам
```
{
  taskAssessments(first: 10, user_id: 2, assessment: 10){
    edges{
      node{
        assessment
        task {
          title
        }
        skillAssessments{
          edges{
            node{
              skillValue
              skill{
                name
              }
            }
          }
        }
      }
    }
    totalCount
    pageInfo {
      endCursor
      hasNextPage
    }
  }
}
```

### Агрегация баллов по времени
```
{
  taskAssessments(first: 10, user_id: 2, createdAt: {after: "2023-07-01", before: "2023-07-31"}){
    edges{
      node{
        assessment
        task {
          title
        }
        skillAssessments{
          edges{
            node{
              skill{
                name
              }
            }
          }
        }
      }
    }
    totalCount
    pageInfo {
      endCursor
      hasNextPage
    }
  }
}
```

### Агрегация баллов по курсам
```
{
  collectionQueryTasks(type: 3){
    edges{
      node{
        title
        assessmentAggregations
      }
    }
  }
}
```

### Агрегация баллов по занятиям
```
{
  collectionQueryTasks(type: 2){
    edges{
      node{
        title
        assessmentAggregations
      }
    }
  }
}
```

### Агрегация баллов по заданиям
```
{
  collectionQueryTasks(type: 1){
    edges{
      node{
        title
        assessmentAggregations
      }
    }
  }
}
```

### Получение пользователей отсортированных по дате создания(от новых к старым)
```
{
  users(order: {createdAt: "DESC" }){
    edges{
      node{
        _id
        surname
        name
        patronymic
      }
    }
  }
}
```

### Количество студентов на курсе
```
{
  tasks(type: 3){
    edges{
      node{
        title
        userCourses{
          totalCount
        }
      }
    }
  }
}
```
