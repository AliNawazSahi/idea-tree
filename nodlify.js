/**!
 * Nodelify 1.0

 * @license MIT
 */

(function (global, factory) {
  typeof exports === "object" && typeof module !== "undefined"
    ? (module.exports = factory())
    : typeof define === "function" && define.amd
      ? define(factory)
      : ((global = global || self), (global.Nodelify = factory()));
})(this, function () {
  ("use strict");

  function Nodelify(el, options) {
    var defaults = {
      fromEl: ".nodelifyList",
      toEl: "#nodelifyCreator",
      nodesDesigner: ".nodelifyNodes",
      ghostPosEl: ".ghostPosEl",
      ghostNodeEl: ".ghostNodeEl"
    };

    var listEl =
      (currentDroppable =
        droppableParent =
        oldStyle =
        nodeEls =
        selected =
        shiftX =
        shiftY =
        null);

    var isDragging = false;

    if (!(el && el.nodeType && el.nodeType === 1)) {
      throw "Nodelify: `el` must be an HTMLElement, not ".concat(
        {}.toString.call(el)
      );
    }

    var self = this;
    this.el = el;
    this.options = { ...defaults, ...options };

    function _generateUuid() {
      return "xxxxxxxx-xxx".replace(/[xy]/g, function (c) {
        var r = (Math.random() * 16) | 0,
          v = c == "x" ? r : (r & 0x3) | 0x8;
        return v.toString(16);
      });
    }

    function _createGhostPosHolder(s) {
      var p = s.parentNode;

      var g = document.createElement("div");
      g.className = "div-box ".concat(self.options.ghostPosEl.slice(1));
      g.style.background = "white";
      g.style.borderWidth = "0px";
      g.id = _generateUuid();

      g.setAttribute("style", oldStyle);
      g.style.position = "relative";
      p.insertBefore(g, s);

      return;
    }

    function _createGhostNode(elem) {
      if (_hasClass(elem, "nodelifyNodes") && elem.children.length > 0) return;

      var g = document.createElement("div");

      g.className = "div-box div-dashed ".concat(
        self.options.ghostNodeEl.slice(1)
      );
      g.style.background = "white";
      g.id = _generateUuid();
      g.style.pointerEvents = "none";
      elem.appendChild(g);
    }

    function _removeGhostNode() {
      var n = document.querySelector(self.options.ghostNodeEl);
      if (n) n.remove();
    }

    function _removeGhostPosEl() {
      var n = document.querySelector(self.options.ghostPosEl);
      if (n) n.remove();
    }

    function _enterDroppable(elem) {
      if (_hasClass(elem, "nodelifyList")) return;
      elem.style.background = "pink";
      _createGhostNode(elem);
    }

    function _leaveDroppable(elem) {
      elem.style.background = "";
      currentDroppable = null;
      _removeGhostNode();
    }

    function _moveDraggableToOriginalPos() {
      var g = document.querySelector(self.options.ghostPosEl);
      var p = g.parentNode;

      selected.setAttribute("style", "");
      selected.setAttribute("style", oldStyle);
      selected.style.opacity = 1;
      p.insertBefore(selected, g);
      g.remove();
    }

    function _isDropPointAcceptable(d) {
      var acceptable = true;
      if (d == null) return false;

      if (!_hasClass(d, "droppable")) acceptable = false;

      if (
        _hasClass(d, self.options.nodesDesigner.slice(1)) &&
        d.children.length == 1 &&
        _hasClass(d.children[0], "droppable")
      )
        acceptable = false;

      if (
        _hasClass(d, self.options.fromEl.slice(1)) &&
        !d.querySelector(self.options.ghostPosEl)
      )
        acceptable = true;

      return acceptable;
    }

    function _removeNodeChildren(n) {


      let toRemove = [];

      n.querySelectorAll(".droppable-container").forEach(e => e.remove());
      n.querySelectorAll(".line").forEach(e => e.remove());

      // console.log(n.children);
      // for(var i = 0; i < n.children.length; i++){

      //   if(!_hasClass(n.children[i], "note-title") || !_hasClass(n.children[i]), "note-content"){

      //     toRemove.push(n.children[i]);


      //   }
      // }

      // console.log(toRemove);


      // toRemove.forEach(e => e.remove());

      // while (n.children.length > 0) {
      //   var lines = document.querySelectorAll(



      //     `[${n.lastChild.getAttribute("line")}=""]`


      //   );
      //   lines.forEach((element) => {
      //     element.remove();
      //   });


      //   n.lastChild.remove();
      // }
    }

    function _removeSelectedNodes(d) {
      var childDroppables = d.querySelectorAll(".droppable");


      d.querySelectorAll("#crossElement").forEach(e => e.remove());
      for (let droppable of childDroppables) {
        droppable.setAttribute("style", "");
        _removeNodeChildren(droppable);
        droppable.className = "div-box moveable";
        listEl[0].appendChild(droppable);
      }

      listEl[0].appendChild(d);
      d.setAttribute("style", "");

      _removeNodeChildren(d);

      d.className = "div-box moveable";
      return;
    }

    function _translateAbsCoordinatetoRelative(d, x, y) {
      let rect = d.getBoundingClientRect();
      return { x: x - rect.x, y: y - rect.y - 2 };
    }

    function _createLine(d, x1, y1, x2, y2) {
      let options = {
        zindex: -1,
        color: "#000000",
        stroke: "2",
        style: "solid",
        class: "line"
      };

      if (x2 < x1) {
        var temp = x1;
        x1 = x2;
        x2 = temp;
        temp = y1;
        y1 = y2;
        y2 = temp;
      }

      var line = document.createElement("div");
      line.className = options.class;

      var length = Math.sqrt((x1 - x2) * (x1 - x2) + (y1 - y2) * (y1 - y2));

      line.style.width = length + "px";
      line.style.borderBottom = options.stroke + "px " + options.style;
      line.style.borderColor = options.color;
      line.style.position = "absolute";
      line.style.zIndex = options.zindex;

      var angle = Math.atan((y2 - y1) / (x2 - x1));

      let point = _translateAbsCoordinatetoRelative(d, x1, y1);

      line.style.top = point.y + 0.5 * length * Math.sin(angle) + "px";
      line.style.left = point.x - 0.5 * length * (1 - Math.cos(angle)) + "px";
      line.style.transform =
        line.style.MozTransform =
        line.style.WebkitTransform =
        line.style.msTransform =
        line.style.OTransform =
        "rotate(" + angle + "rad)";

      line.style.pointerEvents = "none";
      return line;
    }

    function _deleteButtonClicked(event) {
      console.log("delete button worked");
      event.stopPropagation();

      var d = event.target.parentNode;

      _removeSelectedNodes(d);
      return;
    }

    function _addDeleteBtn(d) {

      // let crossElement = "<div style='position:absolute; right:3px; top: 3px;' class='btn btn-danger'>x</div>";


      let crossElement = document.createElement("div");
      crossElement.classList.add('bs_btn_cross');
      crossElement.id = "crossElement";
      crossElement.textContent = "x";





      d.appendChild(crossElement);


      // Add click listener
      crossElement.addEventListener("click", _deleteButtonClicked);



    }

    function _addNode(d) {
      selected.setAttribute("style", "");
      selected.className = "node-box droppable";

      if (!_hasClass(d, self.options.nodesDesigner.slice(1))) {
        _createChildDroppable(d);
        _calculateDroppableContainerPosition(d, false);
        if (droppableParent) {
          _calculateDroppableContainerPosition(
            droppableParent.parentNode,
            false
          );
        }
      } else {
        d.appendChild(selected);
        selected.setAttribute("data-node-level", 0);

        _createDroppableContainer(selected);
        _addDeleteBtn(selected);

      }

      /**
       * Here to work for width
       */
      var root = document.querySelector(self.options.nodesDesigner);

      const selectedBottom = selected.getBoundingClientRect().bottom;
      const rootHeight = root.getBoundingClientRect().height;

      if (selectedBottom > rootHeight / 2) {
        root.style.height = `${rootHeight * 1.5}px`;
      }

      d.style.background = "";
    }
    function _generateRandomString() {
      const characters = "abcdefghijklmnopqrstuvwxyz";
      let randomString = "";

      for (let i = 0; i < 8; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        randomString += characters.charAt(randomIndex);
      }

      return randomString;
    }

    function _createDroppableContainer(s) {
      if (s.querySelector('.droppable-container')) return;
      // if (s.children.length > 0) return;
      var dc = document.createElement("div");
      dc.className = "droppable-container";
      dc.setAttribute("line", _generateRandomString());
      // var dcFirstChild = s.firstChild;
      s.prepend(dc);
      // s.appendChild(dc);

    }

    function _makeNodeLevelAdjustment(d) {
      var c = d.children[0].children;
      d.setAttribute(
        "data-node-level",
        d.parentElement.parentElement.getAttribute("data-node-level") * 1 + 1
      );

      for (var i = 0; i < c.length; i++) {
        c[i].setAttribute(
          "data-node-level",
          c[i].parentElement.parentElement.getAttribute("data-node-level") * 1 +
          1
        );
        if (c[i].children[0].children.length > 0)
          _makeNodeLevelAdjustment(c[i]);
      }
    }
    function _createChildDroppable(d) {
      d.children[0].appendChild(selected);
      _createDroppableContainer(selected);
      _makeNodeLevelAdjustment(selected);
    }

    function _setLineCoordinateAndCreateLine(d, bounds, colParent) {
      let x1,
        y1,
        x2,
        y2 = 0;

      let rect1 = (rect2 = null);

      rect1 = colParent
        ? colParent.parentElement.getBoundingClientRect()
        : d.getBoundingClientRect();

      rect2 = bounds.getBoundingClientRect();

      x1 = rect1.left + rect1.width / 2;
      x2 = rect2.left + rect2.width / 2;
      y1 = rect1.bottom;
      y2 = rect2.top;

      return _createLine(d, x1, y1, x2, y2, colParent);
    }

    function _detectOverlap(dChild, eChild, s1, s2) {
      if (!dChild || !eChild) return 0;

      var rect1 = dChild.getBoundingClientRect();
      var rect2 = eChild.getBoundingClientRect();

      if (s1 == "left") {
        return rect2[s2] > rect1[s1] ? rect2[s2] - rect1[s1] : 0;
      }

      return rect1[s1] > rect2[s2] ? rect1[s1] - rect2[s2] : 0;
    }

    function _checkForOverlap(d) {
      var ow = 0;

      var dChild = d.children[0].firstChild;
      let ps = d.previousElementSibling;
      while (ps) {
        var psChild = ps.children[0].lastChild;
        ow = _detectOverlap(dChild, psChild, "left", "right");
        if (ow) _handleCollision(d, { d: "p", e: ps, w: ow });
        ps = ps.previousElementSibling;
      }

      dChild = d.children[0].lastChild;
      let ns = d.nextElementSibling;
      while (ns) {
        var nsChild = ns.children[0].firstChild;
        ow = _detectOverlap(dChild, nsChild, "right", "left");
        if (ow) _handleCollision(d, { d: "n", e: ns, w: ow });
        ns = ns.nextElementSibling;
      }

      _checkForCrossBoundaryOverlap(d.children[0]);
    }

    function _findCommonAncestor(dc1, dc2, o, d) {
      var el1 = (el2 = e = null);

      while (dc1 != dc2) {
        el1 = dc1.parentNode;
        dc1 = el1.parentNode;
        el2 = dc2.parentNode;
        dc2 = el2.parentNode;
      }

      e = el1;
      if (d == "n") e = el2;

      _handleCollision(e, { d: d, e: e, w: o });
    }

    function _checkForCrossBoundaryOverlap(dc) {
      var nodesAtSelectedNodeLevel = document
        .querySelector(self.options.toEl)
        .querySelectorAll(
          `[data-node-level="${selected.getAttribute("data-node-level")}"]`
        );

      nodesAtSelectedNodeLevel.forEach((e) => {
        var ow = 0;
        var rect1 = selected.parentNode.getBoundingClientRect();
        var rect2 = e.parentElement.getBoundingClientRect();

        if (rect2.right < rect1.right) {
          ow = _detectOverlap(
            selected.parentNode,
            e.parentElement,
            "left",
            "right"
          );
          if (ow)
            _findCommonAncestor(selected.parentNode, e.parentElement, ow, "p");
        }
      });

      nodesAtSelectedNodeLevel.forEach((e) => {
        var ow = 0;
        var rect1 = selected.parentNode.getBoundingClientRect();
        var rect2 = e.parentElement.getBoundingClientRect();

        if (rect2.left > rect1.left) {
          ow = _detectOverlap(
            selected.parentNode,
            e.parentNode,
            "right",
            "left"
          );
          if (ow)
            _findCommonAncestor(selected.parentNode, e.parentElement, ow, "n");
        }
      });
    }

    function _moveNextNodesBy(r, a) {
      let ns = r.nextElementSibling;
      var left = 0;
      while (ns) {
        left = parseInt(ns.style.left) + a;
        ns.style.left = `${left}px`;
        ns = ns.nextElementSibling;
      }
    }

    function _handleCollision(d, o) {
      var left = 0;

      left = parseInt(d.parentNode.style.left) - (o.w + 80) / 2;
      d.parentNode.style.left = `${left}px`;
      if (o.d == "p") {
        left = parseInt(d.style.marginLeft) + o.w + 80;
        d.style.marginLeft = `${left}px`;
        _moveNextNodesBy(d, 80);
      }
      if (o.d == "n") {
        left = parseInt(o.e.style.marginLeft) + o.w + 80;
        o.e.style.marginLeft = `${left}px`;
        _moveNextNodesBy(o.e, 80);
      }

      _drawLines(
        d.parentElement.parentElement,
        d.parentElement.parentElement.children[0]
      );
    }

    function _calculateDroppableContainerPosition(d, t) {
      _removeGhostPosEl();

      var dc = d.children[0];
      var c = dc.children;
      var l = c.length;
      var htw = 0;

      if (dc.firstChild != dc.lastChild) dc.lastChild.style.marginLeft = "80px";
      if (l > 0) {
        dc.firstChild.style.marginLeft = "";
        htw = (dc.getBoundingClientRect().width - 200) / 2;
      }

      dc.style.left = `${htw * -1}px`;
      if (!t) _checkForOverlap(d);

      _drawLines(d, dc);
    }

    function _drawLines(d, dc) {
      var c = dc.children;
      var l = c.length;

      var pL = document.querySelectorAll(`[${dc.getAttribute("line")}=""]`);
      pL.forEach((e) => e.remove());

      for (var i = 0; i < l; i++) {
        line = _setLineCoordinateAndCreateLine(d, c[i]);
        line.setAttribute(dc.getAttribute("line"), "");
        dc.parentNode.appendChild(line);
      }
    }
    function _mouseUp(event) {
      if (_hasClass(event.target, "droppable-container")) return;

      if (selected == null) {
        isDragging = false;
        return;
      }
      selected.style.opacity = 1;
      document.removeEventListener("mousemove", _mouseMove);
      selected.removeEventListener("mouseUp", _mouseUp);

      if (
        _isMouseMouseOutOfWindow(event) ||
        !_isDropPointAcceptable(currentDroppable)
      ) {


        _moveDraggableToOriginalPos();



        if (currentDroppable) currentDroppable.style.background = "";
        currentDroppable = selected = null;
        isDragging = false;
        _removeGhostPosEl();
        return;
      }

      if (_hasClass(currentDroppable, self.options.fromEl.slice(1))) {
        _removeSelectedNodes(selected);

        if (droppableParent) {
          _calculateDroppableContainerPosition(
            droppableParent.parentNode,
            true
          );
        }

        currentDroppable = null;
      }

      if (currentDroppable) {
        _removeGhostNode();
        _addNode(currentDroppable);
        currentDroppable = null;
      }

      _removeGhostPosEl();
      selected = null;
      isDragging = false;
    }

    function _isMouseMouseOutOfWindow(event) {
      const mouseX = event.clientX;
      const mouseY = event.clientY;
      const windowWidth = window.innerWidth;
      const windowHeight = window.innerHeight;

      if (
        mouseX < 0 ||
        mouseX > windowWidth ||
        mouseY < 0 ||
        mouseY > windowHeight
      )
        return true;

      return false;
    }


    function _mouseMove(event) {
      _moveAt(event.pageX, event.pageY);

      if (_isMouseMouseOutOfWindow(event)) {
        _mouseUp(event);
        return;
      }

      selected.hidden = true;
      let elemBelow = document.elementFromPoint(event.clientX, event.clientY);
      selected.hidden = false;

      if (!elemBelow) return;

      let droppableBelow = null;
      droppableBelow = elemBelow.closest(".node-box");

      if (
        _hasClass(elemBelow, "droppable") ||
        _hasClass(elemBelow, self.options.nodesDesigner.slice(1))
      )
        droppableBelow = elemBelow;

      if (currentDroppable != droppableBelow) {
        if (currentDroppable) {
          _leaveDroppable(currentDroppable);
        }
        currentDroppable = droppableBelow;
        if (currentDroppable) {
          _enterDroppable(currentDroppable);
        }
      }
    }
    function _moveAt(pageX, pageY) {
      selected.style.left = pageX - shiftX + "px";
      selected.style.top = pageY - shiftY + "px";
    }

    function _dragStart(e) {

      // console.log(isDragging);
      if (isDragging) return;
      if (e.target.id == "crossElement") return;
      // if (_hasClass(e.target, "three-dots")) return;
      // if (_hasClass(e.target, "droppable-container")) return;
      if (e.target.id == "resizeElement") return;
      if (e.target.id == "viewMore") return;


      //work
      // selected = e.target
      selected = e.target.closest('.droppable') || e.target.closest('.moveable');



      // console.log(selected);


      if (selected == null) return;




      isDragging = true;

      selected.style.opacity = 0.4;
      oldStyle = selected.getAttribute("style");



      if (
        selected.children.length > 0 &&
        _hasClass(selected.parentNode, "droppable-container")
      )
        droppableParent = selected.parentNode;

      shiftX = e.clientX - selected.getBoundingClientRect().left;
      shiftY = e.clientY - selected.getBoundingClientRect().top;

      _createGhostPosHolder(selected);

      selected.style.position = "absolute";
      selected.style.zIndex = 1000;
      selected.style.marginLeft = "";
      document.body.append(selected);

      _moveAt(e.pageX, e.pageY);

      document.addEventListener("mousemove", _mouseMove);
      selected.addEventListener("mouseup", _mouseUp);
    }

    function _getElements() {
      let nodes = [];
      let els = (listEl = document.getElementsByClassName(
        self.options.fromEl.slice(1)
      ));

      for (let el of els) {
        nodes = [...nodes, ...el.children];
      }
      return nodes;
    }

    function _hasClass(e, c) {
      return e.classList.contains(c);
    }

    function _checkRequirements(el) {
      var nodes = [];

      for (let i of el) {
        if (_hasClass(i, "nodelifyList")) nodes.push(i);
        if (i.id == "nodelifyCreator") nodes.push(i);
      }

      if (nodes.length < 2)
        throw "Nodelify: `el` must contain child elements with the an element with the class: ".concat(
          self.options.fromEl,
          " & an element with the Id ",
          self.options.toEl
        );

      el.className = self.options.nodesDesigner.slice(1);
      return el;
    }

    function _noteMouseMove(e) {
      // console.log("mousemove called");
      // console.log(e);

      // console.log(_translateAbsCoordinatetoRelative(e.target, e.clientX, e.clientY));

    }



    function _startResizingNote(e) {




      const width = startWidth + e.clientX - startX;
      const height = startHeight + e.clientY - startY;
      selected.style.width = width + "px";
      selected.style.height = height + "px";







    }

    function _endResizeNote(e) {

      // remove the events listners
      selected = null;



      document.documentElement.removeEventListener("mousemove", _startResizingNote, false);
      document.documentElement.removeEventListener("mouseup", _endResizeNote, false);



      // document.removeEventListener('mousemove', _startResizingNote);
      // document.removeEventListener('mouseup', _endResizeNote);


    }


    let startX, startY, startWidth, startHeight;

    function _noteResizeStart(e) {
      e.stopPropagation();
      e.preventDefault();
      // if (e.target !== selected) {
      //   return;
      // }

      // e ka nearest droppable find 
      selected = e.target.closest('.droppable');
      // `console.log("_noteResizeStart");
      // console.log(selected);`



      startX = e.clientX;
      startY = e.clientY;
      startWidth = parseInt(document.defaultView.getComputedStyle(selected).width, 10);
      startHeight = parseInt(document.defaultView.getComputedStyle(selected).height, 10);
      document.documentElement.addEventListener("mousemove", _startResizingNote, false);
      document.documentElement.addEventListener("mouseup", _endResizeNote, false);






    }


    this.registerEventsOnElements = function (e) {
      e.addEventListener("mousedown", _dragStart.bind(self));
      // e.addEventListener("mousemove", _noteMouseMove.bind(self));


      e.querySelector('.ic-resize-svg').addEventListener('mousedown', _noteResizeStart);
      // to stop the default dragging event
      e.querySelector('.ic-resize-svg').addEventListener("dragstart", (e) => false);

      e.addEventListener("dragstart", (e) => false);

      let crossElem = document.getElementById('crossElement');

      if (crossElem !== null)
        crossElem.addEventListener("click", _deleteButtonClicked);
    }

    function _registerEvents() {
      let n = _getElements();
      n.forEach((e) => {


        self.registerEventsOnElements(e);
        // e.addEventListener("mousedown", _dragStart.bind(self));
        // // e.addEventListener("mousemove", _noteMouseMove.bind(self));


        // e.querySelector('.ic-resize-svg').addEventListener('mousedown', _noteResizeStart);
        // // to stop the default dragging event
        // e.querySelector('.ic-resize-svg').addEventListener("dragstart", (e) => false);

        // e.addEventListener("dragstart", (e) => false);
      });
    }

    this.initialize = function () {
      _checkRequirements(el.children);
      _registerEvents();
    };
  }

  Nodelify.prototype = function createBranchItem(item) { };

  return Nodelify;
});
