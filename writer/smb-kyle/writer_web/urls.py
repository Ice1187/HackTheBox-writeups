from django.conf.urls import url
from writer_web import views

urlpatterns = [
    url(r'^$', views.home_page, name='home'),
]
