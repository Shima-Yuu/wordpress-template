$(function() {
  $('.fn-sidebar .fn-menu').each(function(i, el) {
    const fileName = $(el).attr('href');
    const innerHtml = $(el).html();
    if(fileName.indexOf('component') !== -1) {
      $(el).html( '<span class="label label-component">Component</span><br>' + innerHtml)
    } else if(fileName.indexOf('project') !== -1) {
      $(el).html( '<span class="label label-project">Project</span><br>' + innerHtml)
    } else {
      $(el).html( '<span class="label label-default">Default</span><br>' + innerHtml)
    }
  })

  $('a:not([href*="#"])').not('.fn-menu').each(function(i,el) {
    $(el).attr('target', '_blank');
  })
  
  $('a[href*="#"]').on('click', function () {
    const href = $(this).attr('href');
    const target = href == '#' || href == '' ? 'html' : href;
    const position = $(target).offset().top - 64;
    $('body,html').animate({ scrollTop: position }, 500, 'swing');

    return false;
  });
})